<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

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

        $response->assertRedirect(route('coordinacion.companies.index'));
        $this->assertAuthenticatedAs($coordinator);
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

    public function test_verified_supervisor_can_sign_in_to_company_letters(): void
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
        ])->assertRedirect(route('supervisor.practice-letters.index'));

        $this->assertAuthenticatedAs($supervisor);
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

    public function test_coordinator_routes_require_authentication_and_coordinator_access(): void
    {
        $this->get(route('coordinacion.companies.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => UserRole::Student]))
            ->get(route('coordinacion.companies.index'))
            ->assertForbidden();
    }
}
