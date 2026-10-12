<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\PracticeEvaluation;
use App\Models\PracticeRequest;
use App\Models\User;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_creates_four_role_accounts_and_a_completed_prerequisite_scenario_idempotently(): void
    {
        Storage::fake('local');

        Artisan::call('db:seed', ['--class' => DemoDataSeeder::class]);

        $this->assertSame(4, User::query()->whereIn('email', [
            'coordinacion@nanis.test',
            'asesor@nanis.test',
            'supervisor@nanis.test',
            'alumno@nanis.test',
        ])->count());
        $this->assertSame(UserRole::Tutor, User::query()->where('email', 'asesor@nanis.test')->firstOrFail()->role);
        $this->assertNotNull(User::query()->where('email', 'supervisor@nanis.test')->firstOrFail()->email_verified_at);
        $this->assertDatabaseCount('practice_requests', 1);
        $request = PracticeRequest::query()->sole();
        $this->assertSame('approved', $request->status->value);
        $this->assertSame('approved', $request->practiceReport->approvedVersion->status->value);
        $this->assertSame(0, PracticeEvaluation::query()->count());
        $this->assertTrue(Storage::disk('local')->exists('practice-acceptances/demo/aceptacion-demo.pdf'));

        Artisan::call('db:seed', ['--class' => DemoDataSeeder::class]);
        $this->assertSame(4, User::query()->whereIn('email', [
            'coordinacion@nanis.test',
            'asesor@nanis.test',
            'supervisor@nanis.test',
            'alumno@nanis.test',
        ])->count());
        $this->assertDatabaseCount('practice_requests', 1);
    }
}
