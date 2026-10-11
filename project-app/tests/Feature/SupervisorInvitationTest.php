<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Coordinator\Companies\InviteSupervisor;
use App\Mail\SupervisorInvitationMail;
use App\Models\Company;
use App\Models\SupervisorInvitation;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class SupervisorInvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_coordinator_can_invite_supervisor_and_invitation_verifies_email_and_sets_password(): void
    {
        Mail::fake();
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $company = Company::query()->create(['name' => 'Hotel Uno']);
        $this->actingAs($coordinator);

        $this->get(route('coordinacion.companies.supervisors.invite', $company))
            ->assertOk()
            ->assertSee('Invitar supervisor');

        Livewire::test(InviteSupervisor::class, ['company' => $company])
            ->set('email', 'supervisor@example.test')
            ->call('sendInvitation')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.companies.index'));

        $invitation = SupervisorInvitation::query()->sole();
        $this->assertSame($company->id, $invitation->company_id);
        $this->assertSame($coordinator->id, $invitation->coordinator_id);
        $this->assertTrue($invitation->expires_at->between(now()->addDays(6), now()->addDays(7)));

        $token = null;
        Mail::assertSent(SupervisorInvitationMail::class, function (SupervisorInvitationMail $mail) use (&$token): bool {
            $token = $mail->token;

            return $mail->invitation->email === 'supervisor@example.test';
        });
        $this->assertNotNull($token);
        $this->assertSame(hash('sha256', $token), $invitation->token_hash);

        $this->get(route('supervisor.invitations.accept', $token))
            ->assertOk()
            ->assertSee('Hotel Uno')
            ->assertSee('supervisor@example.test');

        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->post(route('supervisor.invitations.store', $token), [
            'name' => 'Supervisora Uno',
            'password' => 'SecurePassword123',
            'password_confirmation' => 'SecurePassword123',
        ])
            ->assertRedirect(route('supervisor.practice-letters.index'));

        $supervisor = User::query()->where('email', 'supervisor@example.test')->firstOrFail();
        $this->assertSame(UserRole::Supervisor, $supervisor->role);
        $this->assertSame($company->id, $supervisor->company_id);
        $this->assertNotNull($supervisor->email_verified_at);
        $this->assertAuthenticatedAs($supervisor);
        $this->assertNotNull($invitation->fresh()->accepted_at);

        $this->post(route('supervisor.logout'))->assertRedirect(route('login'));
        $this->post(route('supervisor.invitations.store', $token), [
            'name' => 'Supervisora Uno',
            'password' => 'SecurePassword123',
            'password_confirmation' => 'SecurePassword123',
        ])->assertNotFound();
    }

    public function test_only_coordinator_can_send_invitation_and_expired_link_is_unavailable(): void
    {
        $company = Company::query()->create(['name' => 'Hotel Uno']);
        $student = User::factory()->create(['role' => UserRole::Student]);

        $this->actingAs($student)
            ->get(route('coordinacion.companies.supervisors.invite', $company))
            ->assertForbidden();

        $invitation = $company->supervisorInvitations()->create([
            'coordinator_id' => User::factory()->create(['role' => UserRole::Coordinator])->id,
            'email' => 'expired@example.test',
            'token_hash' => hash('sha256', 'expired-token'),
            'expires_at' => now()->subMinute(),
        ]);

        $this->get(route('supervisor.invitations.accept', 'expired-token'))->assertNotFound();
        $this->assertNull($invitation->accepted_at);
    }

    public function test_invitation_requires_confirmed_password_of_minimum_length(): void
    {
        $company = Company::query()->create(['name' => 'Hotel Uno']);
        $invitation = $company->supervisorInvitations()->create([
            'coordinator_id' => User::factory()->create(['role' => UserRole::Coordinator])->id,
            'email' => 'supervisor@example.test',
            'token_hash' => hash('sha256', 'valid-token'),
            'expires_at' => now()->addDay(),
        ]);

        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->post(route('supervisor.invitations.store', 'valid-token'), [
            'name' => 'Supervisora Uno',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])
            ->assertSessionHasErrors(['password']);

        $this->assertDatabaseCount('users', 1);
        $this->assertNull($invitation->fresh()->accepted_at);
    }
}
