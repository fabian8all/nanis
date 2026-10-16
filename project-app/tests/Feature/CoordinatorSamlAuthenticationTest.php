<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\Saml\SamlAuthService;
use App\Services\Saml\SamlUserProvisioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorSamlAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_saml_login_redirects_to_practice_tracking(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $saml = \Mockery::mock(SamlAuthService::class);
        $saml->shouldReceive('consumeResponse')
            ->once()
            ->with('relay-state')
            ->andReturn(['uCorreo' => [$coordinator->email]]);
        $this->app->instance(SamlAuthService::class, $saml);

        $provisioner = \Mockery::mock(SamlUserProvisioner::class);
        $provisioner->shouldReceive('authenticateOrCreate')
            ->once()
            ->andReturn($coordinator);
        $this->app->instance(SamlUserProvisioner::class, $provisioner);

        $this->post(route('auth.saml.acs'), [
            'RelayState' => 'relay-state',
            'SAMLResponse' => 'valid-in-this-test',
        ])
            ->assertRedirect(route('coordinacion.practice-tracking.index'));

        $this->assertAuthenticatedAs($coordinator);
    }
}
