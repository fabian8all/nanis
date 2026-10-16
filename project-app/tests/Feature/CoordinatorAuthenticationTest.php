<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class CoordinatorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_coordinator_can_sign_in(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $coordinator = User::factory()->create([
            'role' => UserRole::Coordinator,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $coordinator->email,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $response->assertRedirect(route('coordinacion.practice-tracking.index'));
        $this->assertAuthenticatedAs($coordinator);

        $this->get(route('coordinacion.practice-tracking.index'))->assertOk();
    }

    public function test_user_with_an_invalid_password_cannot_sign_in(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $user = User::factory()->create([
            'role' => UserRole::Tutor,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'IncorrectPassword1!',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_student_can_sign_in_with_password_only_in_local_and_testing_environments(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $student = User::factory()->create([
            'role' => UserRole::Student,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'CorrectHorseBattery1!',
        ])->assertRedirect(route('alumno.companies.index'));

        $this->assertAuthenticatedAs($student);
    }

    public function test_student_login_destination_uses_the_latest_request_status(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $destinations = [
            [PracticeRequestStatus::Pending, 'alumno.practice-tracking.index'],
            [PracticeRequestStatus::Returned, 'alumno.practice-request.show'],
            [PracticeRequestStatus::Approved, 'alumno.practice-tracking.index'],
            [PracticeRequestStatus::Completed, 'alumno.practice-tracking.index'],
        ];

        foreach ($destinations as [$status, $route]) {
            $student = User::factory()->create([
                'role' => UserRole::Student,
                'password' => 'CorrectHorseBattery1!',
            ]);
            $this->createPracticeRequest($student, $status);

            $this->withSession(['url.intended' => route('alumno.companies.index')])
                ->post(route('login.store'), [
                    'email' => $student->email,
                    'password' => 'CorrectHorseBattery1!',
                ])
                ->assertRedirect(route($route))
                ->assertSessionMissing('url.intended');
        }
    }

    public function test_student_login_ignores_another_students_request_state(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $student = User::factory()->create([
            'role' => UserRole::Student,
            'password' => 'CorrectHorseBattery1!',
        ]);
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $this->createPracticeRequest($otherStudent, PracticeRequestStatus::Pending);

        $this->post(route('login.store'), [
            'email' => $student->email,
            'password' => 'CorrectHorseBattery1!',
        ])->assertRedirect(route('alumno.companies.index'));
    }

    public function test_student_password_sign_in_is_rejected_in_production(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $student = User::factory()->create([
            'role' => UserRole::Student,
            'password' => 'CorrectHorseBattery1!',
        ]);
        $this->app->instance('env', 'production');

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $student->email,
                'password' => 'CorrectHorseBattery1!',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_verified_supervisor_can_sign_in_to_practice_tracking(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $company = Company::query()->create(['name' => 'Hotel Supervisor']);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->post(route('login.store'), [
            'email' => $supervisor->email,
            'password' => 'CorrectHorseBattery1!',
        ])->assertRedirect(route('supervisor.practice-tracking.index'));

        $this->assertAuthenticatedAs($supervisor);
        $this->get(route('supervisor.practice-tracking.index'))->assertOk();
    }

    public function test_tutor_can_sign_in_to_practice_reports(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $tutor = User::factory()->create([
            'role' => UserRole::Tutor,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->post(route('login.store'), [
            'email' => $tutor->email,
            'password' => 'CorrectHorseBattery1!',
        ])->assertRedirect(route('tutor.practice-reports.index'));

        $this->assertAuthenticatedAs($tutor);
    }

    public function test_unverified_supervisor_cannot_sign_in(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $company = Company::query()->create(['name' => 'Hotel Supervisor']);
        $supervisor = User::factory()->unverified()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $supervisor->email,
                'password' => 'CorrectHorseBattery1!',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_supervisor_without_a_company_cannot_sign_in(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => null,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $supervisor->email,
                'password' => 'CorrectHorseBattery1!',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_coordinator_routes_require_authentication_and_coordinator_access(): void
    {
        $this->get(route('coordinacion.companies.index'))
            ->assertRedirect(route('login'));

        $this->get(route('coordinacion.practice-tracking.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => UserRole::Student]))
            ->get(route('coordinacion.companies.index'))
            ->assertForbidden();

        $this->get(route('coordinacion.practice-tracking.index'))
            ->assertForbidden();
    }

    private function createPracticeRequest(User $student, PracticeRequestStatus $status): void
    {
        $company = Company::query()->create(['name' => 'Hotel '.$student->id]);
        $student->practiceRequests()->create([
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-11-01',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2027-01-31',
            'status' => $status,
            'non_responsiva_accepted_at' => now(),
        ]);
    }
}
