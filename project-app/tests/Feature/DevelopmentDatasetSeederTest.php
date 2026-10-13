<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeAcceptanceSubmission;
use App\Models\PracticePlanVersion;
use App\Models\PracticeReportVersion;
use App\Models\PracticeRequest;
use App\Models\User;
use Database\Seeders\DevelopmentDatasetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class DevelopmentDatasetSeederTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_PASSWORD = 'development-seed-password';

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'demo.password' => self::TEST_PASSWORD,
            'demo.coordinator_email' => 'coordinacion@example.test',
            'demo.supervisor_email' => 'supervisor1@example.test',
            'demo.user_email_domain' => 'example.test',
        ]);
        Storage::fake('local');
    }

    public function test_development_seeder_creates_requested_accounts_and_practices_at_multiple_stages_idempotently(): void
    {
        Artisan::call('db:seed', ['--class' => DevelopmentDatasetSeeder::class]);

        $this->assertSame(20, Company::query()->count());
        $this->assertSame(57, User::query()->count());
        $this->assertSame(1, User::query()->where('role', UserRole::Coordinator)->count());
        $this->assertSame(5, User::query()->where('role', UserRole::Tutor)->count());
        $this->assertSame(1, User::query()->where('role', UserRole::Supervisor)->count());
        $this->assertSame(50, User::query()->where('role', UserRole::Student)->count());
        $this->assertSame(50, PracticeRequest::query()->count());
        $this->assertSame(5, PracticeAcceptanceSubmission::query()->where('status', PracticeAcceptanceStatus::PendingReview)->count());
        $this->assertSame(20, PracticeAcceptanceSubmission::query()->where('status', PracticeAcceptanceStatus::Accepted)->count());
        $this->assertSame(5, PracticePlanVersion::query()->where('status', PracticePlanStatus::PendingSupervisorReview)->count());
        $this->assertSame(5, PracticePlanVersion::query()->where('status', PracticePlanStatus::PendingCoordinatorReview)->count());
        $this->assertSame(10, PracticeReportVersion::query()->count());
        $this->assertSame(5, PracticeReportVersion::query()->where('status', PracticeReportStatus::PendingTutorReview)->count());
        $this->assertSame(5, PracticeReportVersion::query()->where('status', PracticeReportStatus::Approved)->count());
        $this->assertSame(10, PracticeRequest::query()->where('status', PracticeRequestStatus::Pending)->count());
        $this->assertSame(5, PracticeRequest::query()->where('status', PracticeRequestStatus::Returned)->count());
        $this->assertSame(35, PracticeRequest::query()->where('status', PracticeRequestStatus::Approved)->count());

        $coordinator = User::query()->where('email', 'coordinacion@example.test')->firstOrFail();
        $supervisor = User::query()->where('email', 'supervisor1@example.test')->firstOrFail();
        $firstStudent = User::query()->where('email', 'alumno1@example.test')->firstOrFail();
        $lastStudent = User::query()->where('email', 'alumno50@example.test')->firstOrFail();
        $this->assertTrue(Hash::check(self::TEST_PASSWORD, $coordinator->password));
        $this->assertTrue(Hash::check(self::TEST_PASSWORD, $supervisor->password));
        $this->assertTrue(Hash::check(self::TEST_PASSWORD, $firstStudent->password));
        $this->assertNotNull($supervisor->company_id);
        $this->assertSame(
            27,
            PracticeRequest::query()->where('company_id', $supervisor->company_id)->count(),
        );
        $this->assertSame(0, $firstStudent->practiceRequests()->firstOrFail()->practiceReport()->count());
        $this->assertSame(PracticeReportStatus::Approved, $lastStudent->practiceRequests()->firstOrFail()->practiceReport->approvedVersion->status);
        Storage::disk('local')->assertExists(
            $lastStudent->practiceRequests()->firstOrFail()->latestAcceptanceSubmission->file_path,
        );

        Artisan::call('db:seed', ['--class' => DevelopmentDatasetSeeder::class]);
        $this->assertSame(20, Company::query()->count());
        $this->assertSame(57, User::query()->count());
        $this->assertSame(50, PracticeRequest::query()->count());
        $this->assertSame(25, PracticeAcceptanceSubmission::query()->count());
        $this->assertSame(20, PracticePlanVersion::query()->count());
        $this->assertSame(10, PracticeReportVersion::query()->count());
    }

    public function test_development_seeder_refuses_to_run_outside_local_and_testing(): void
    {
        $this->app->instance('env', 'production');

        try {
            app(DevelopmentDatasetSeeder::class)->run();
            $this->fail('The development seeder should reject production.');
        } catch (RuntimeException $exception) {
            $this->assertSame(
                'Development data may only be seeded in local or testing environments.',
                $exception->getMessage(),
            );
        }

        $this->assertDatabaseCount('companies', 0);
        $this->assertDatabaseCount('users', 0);
    }
}
