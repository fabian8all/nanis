<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeRequest;
use App\Models\User;
use App\Services\Saml\InvalidSamlIdentityException;
use App\Services\Saml\SamlAuthService;
use App\Services\Saml\SamlUserProvisioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SamlStudentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulator_provisions_a_verified_student_account(): void
    {
        config([
            'services.saml2.simulator_enabled' => true,
            'services.saml2.simulator_email' => 'alumno@example.test',
            'services.saml2.simulator_name' => 'Alumno de prueba',
            'services.saml2.simulator_firstname' => 'Nombre de prueba',
            'services.saml2.simulator_lastname' => 'Apellido de prueba',
        ]);

        $this->get(route('auth.saml.simulate'))
            ->assertRedirect(route('alumno.companies.index'));

        $user = User::query()->where('email', 'alumno@example.test')->firstOrFail();

        $this->assertSame(UserRole::Student, $user->role);
        $this->assertSame('Alumno de prueba', $user->name);
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_simulator_maps_all_configured_identity_attributes(): void
    {
        config([
            'services.saml2.simulator_enabled' => true,
            'services.saml2.simulator_email' => 'alumno@example.test',
            'services.saml2.simulator_name' => 'Nombre completo de prueba',
            'services.saml2.simulator_firstname' => 'Nombre',
            'services.saml2.simulator_lastname' => 'Apellido',
        ]);

        $user = User::factory()->create();
        $provisioner = \Mockery::mock(SamlUserProvisioner::class);
        $provisioner->shouldReceive('authenticateOrCreate')
            ->once()
            ->with([
                'uCorreo' => ['alumno@example.test'],
                'uNombre' => ['Nombre completo de prueba'],
                'sn' => ['Apellido'],
                'givenName' => ['Nombre Apellido'],
                'displayName' => ['Nombre'],
            ])
            ->andReturn($user);
        $this->app->instance(SamlUserProvisioner::class, $provisioner);

        $this->get(route('auth.saml.simulate'))
            ->assertRedirect(route('alumno.companies.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_simulator_routes_by_current_request_status_and_ignores_stale_intended_url(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        config([
            'services.saml2.simulator_enabled' => true,
            'services.saml2.simulator_email' => $student->email,
        ]);
        $request = $this->createPracticeRequest($student, PracticeRequestStatus::Pending);

        $this->withSession(['url.intended' => route('alumno.companies.index')])
            ->get(route('auth.saml.simulate'))
            ->assertRedirect(route('alumno.practice-tracking.index'))
            ->assertSessionMissing('url.intended');

        $request->update(['status' => PracticeRequestStatus::Returned]);
        $this->withSession(['url.intended' => route('alumno.practice-tracking.index')])
            ->get(route('auth.saml.simulate'))
            ->assertRedirect(route('alumno.practice-request.show'))
            ->assertSessionMissing('url.intended');

        $request->update(['status' => PracticeRequestStatus::Completed]);
        $this->withSession(['url.intended' => route('alumno.companies.index')])
            ->get(route('auth.saml.simulate'))
            ->assertRedirect(route('alumno.practice-tracking.index'))
            ->assertSessionMissing('url.intended');
    }

    public function test_acs_routes_authenticated_students_by_their_current_practice_state(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $this->createPracticeRequest($student, PracticeRequestStatus::Approved);
        $saml = \Mockery::mock(SamlAuthService::class);
        $saml->shouldReceive('consumeResponse')
            ->once()
            ->with('relay-state')
            ->andReturn(['uCorreo' => [$student->email]]);
        $this->app->instance(SamlAuthService::class, $saml);
        $provisioner = \Mockery::mock(SamlUserProvisioner::class);
        $provisioner->shouldReceive('authenticateOrCreate')
            ->once()
            ->andReturn($student);
        $this->app->instance(SamlUserProvisioner::class, $provisioner);

        $this->withSession(['url.intended' => route('alumno.companies.index')])
            ->post(route('auth.saml.acs'), [
                'RelayState' => 'relay-state',
                'SAMLResponse' => 'valid-in-this-test',
            ])
            ->assertRedirect(route('alumno.practice-tracking.index'))
            ->assertSessionMissing('url.intended');
    }

    public function test_simulator_reuses_an_existing_account_without_changing_its_role(): void
    {
        $user = User::factory()->create([
            'email' => 'tutor@example.test',
            'role' => UserRole::Tutor,
        ]);
        config([
            'services.saml2.simulator_enabled' => true,
            'services.saml2.simulator_email' => $user->email,
        ]);

        $this->get(route('auth.saml.simulate'))
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame(UserRole::Tutor, $user->fresh()->role);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_simulator_is_unavailable_when_disabled_or_outside_development_environments(): void
    {
        config(['services.saml2.simulator_enabled' => false]);

        $this->get(route('auth.saml.simulate'))->assertNotFound();

        $this->app['env'] = 'production';
        config(['services.saml2.simulator_enabled' => true]);

        $this->get(route('auth.saml.simulate'))->assertNotFound();
    }

    public function test_login_page_shows_the_simulator_below_local_sign_in_when_enabled(): void
    {
        config(['services.saml2.simulator_enabled' => true]);

        $this->get(route('login'))
            ->assertOk()
            ->assertSeeInOrder(['Iniciar sesión', 'Acceso federado UCOL'])
            ->assertSee(route('auth.saml.simulate'));
    }

    public function test_login_page_shows_the_federated_login_below_local_sign_in_when_enabled(): void
    {
        config([
            'services.saml2.simulator_enabled' => false,
            'services.saml2.enabled' => true,
        ]);

        $this->get(route('login'))
            ->assertOk()
            ->assertSeeInOrder(['Iniciar sesión', 'Acceso federado UCOL'])
            ->assertSee(route('auth.saml.login'));
    }

    public function test_saml_login_reports_missing_identity_provider_configuration(): void
    {
        config(['services.saml2.enabled' => false]);

        $this->get(route('auth.saml.login'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('saml');

        $this->assertGuest();
    }

    public function test_saml_login_creates_a_correlated_authentication_request(): void
    {
        config([
            'services.saml2.enabled' => true,
            'services.saml2.idp_entity_id' => 'https://idp.example.test/entity',
            'services.saml2.idp_sso_url' => 'https://idp.example.test/sso',
            'services.saml2.idp_x509_cert' => 'test-certificate',
        ]);

        $response = $this->get(route('auth.saml.login'));
        $response->assertRedirect();

        $redirectUrl = (string) $response->headers->get('Location');
        $query = [];
        parse_str((string) parse_url($redirectUrl, PHP_URL_QUERY), $query);

        $this->assertSame('https', parse_url($redirectUrl, PHP_URL_SCHEME));
        $this->assertSame('idp.example.test', parse_url($redirectUrl, PHP_URL_HOST));
        $this->assertSame('/sso', parse_url($redirectUrl, PHP_URL_PATH));
        $this->assertArrayHasKey('SAMLRequest', $query);
        $this->assertArrayHasKey('RelayState', $query);
        $this->assertMatchesRegularExpression('/\A[a-f0-9]{64}\z/D', $query['RelayState']);

        $this->assertTrue(Cache::has('saml2.authn_request.'.hash('sha256', $query['RelayState'])));
    }

    public function test_acs_rejects_a_response_without_a_matching_login_request(): void
    {
        $this->post(route('auth.saml.acs'), [
            'SAMLResponse' => 'invalid',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('saml');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_acs_rejects_an_invalid_response_with_a_matching_login_request(): void
    {
        config([
            'services.saml2.idp_entity_id' => 'https://idp.example.test/entity',
            'services.saml2.idp_sso_url' => 'https://idp.example.test/sso',
            'services.saml2.idp_x509_cert' => 'test-certificate',
        ]);

        $relayState = bin2hex(random_bytes(32));
        Cache::put('saml2.authn_request.'.hash('sha256', $relayState), '_expected-request', now()->addMinutes(5));

        $this->post(route('auth.saml.acs'), [
            'RelayState' => $relayState,
            'SAMLResponse' => base64_encode('not XML'),
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('saml');

        $this->assertFalse(Cache::has('saml2.authn_request.'.hash('sha256', $relayState)));
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_provisioner_rejects_a_missing_or_invalid_email_claim(): void
    {
        $this->expectException(InvalidSamlIdentityException::class);

        (new SamlUserProvisioner)->authenticateOrCreate([
            'uCorreo' => ['not-an-email'],
        ]);
    }

    public function test_service_provider_metadata_can_be_generated_without_idp_credentials(): void
    {
        $this->get(route('auth.saml.metadata'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/samlmetadata+xml')
            ->assertSee('<md:EntityDescriptor', false);
    }

    private function createPracticeRequest(User $student, PracticeRequestStatus $status): PracticeRequest
    {
        $company = Company::query()->create(['name' => 'Hotel '.$student->id]);

        return $student->practiceRequests()->create([
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
