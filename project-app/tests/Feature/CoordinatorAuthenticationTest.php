<?php

namespace Tests\Feature;

use App\Enums\UserRole;
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

    public function test_non_coordinator_cannot_sign_in(): void
    {
        $this->withoutMiddleware(PreventRequestForgery::class);

        $user = User::factory()->create([
            'role' => UserRole::Student,
            'password' => 'CorrectHorseBattery1!',
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
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
