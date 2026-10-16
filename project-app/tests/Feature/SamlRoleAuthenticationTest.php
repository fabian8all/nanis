<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use App\Services\Saml\SamlAuthService;
use App\Services\Saml\SamlUserProvisioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SamlRoleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_supervisor_saml_login_redirects_to_practice_tracking(): void
    {
        $company = Company::query()->create(['name' => 'Hotel Supervisor']);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
        ]);

        $this->postSamlResponse($supervisor);

        $this->get(route('supervisor.practice-tracking.index'))->assertOk();
    }

    public function test_unverified_supervisor_cannot_access_practice_tracking_after_saml_login(): void
    {
        $company = Company::query()->create(['name' => 'Hotel Supervisor']);
        $supervisor = User::factory()->unverified()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
        ]);

        $this->postSamlResponse($supervisor);

        $this->get(route('supervisor.practice-tracking.index'))->assertForbidden();
    }

    public function test_supervisor_without_a_company_cannot_access_practice_tracking_after_saml_login(): void
    {
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => null,
        ]);

        $this->postSamlResponse($supervisor);

        $this->get(route('supervisor.practice-tracking.index'))->assertForbidden();
    }

    private function postSamlResponse(User $supervisor): void
    {
        $saml = \Mockery::mock(SamlAuthService::class);
        $saml->shouldReceive('consumeResponse')
            ->once()
            ->with('relay-state')
            ->andReturn(['uCorreo' => [$supervisor->email]]);
        $this->app->instance(SamlAuthService::class, $saml);

        $provisioner = \Mockery::mock(SamlUserProvisioner::class);
        $provisioner->shouldReceive('authenticateOrCreate')
            ->once()
            ->andReturn($supervisor);
        $this->app->instance(SamlUserProvisioner::class, $provisioner);

        $this->post(route('auth.saml.acs'), [
            'RelayState' => 'relay-state',
            'SAMLResponse' => 'valid-in-this-test',
        ])
            ->assertRedirect(route('supervisor.practice-tracking.index'));

        $this->assertAuthenticatedAs($supervisor);
    }
}
