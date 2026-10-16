<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Saml\InvalidSamlIdentityException;
use App\Services\Saml\SamlAuthenticationException;
use App\Services\Saml\SamlAuthService;
use App\Services\Saml\SamlConfigurationException;
use App\Services\Saml\SamlUserProvisioner;
use App\Services\StudentPracticeDestination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SamlSessionController extends Controller
{
    public function login(SamlAuthService $saml): RedirectResponse
    {
        try {
            return $saml->initiateLogin();
        } catch (SamlConfigurationException) {
            return redirect()->route('login')->withErrors([
                'saml' => 'El acceso institucional no está configurado.',
            ]);
        }
    }

    public function assertionConsumer(
        Request $request,
        SamlAuthService $saml,
        SamlUserProvisioner $provisioner,
        StudentPracticeDestination $studentPracticeDestination,
    ): RedirectResponse {
        try {
            $attributes = $saml->consumeResponse($request->input('RelayState'));
            $user = $provisioner->authenticateOrCreate($attributes);
        } catch (SamlAuthenticationException|InvalidSamlIdentityException $exception) {
            Log::warning('Institutional SAML authentication was rejected.', [
                'failure' => $exception::class,
            ]);

            return redirect()->route('login')->withErrors([
                'saml' => 'No se pudo validar el acceso institucional. Inténtalo de nuevo o contacta a Coordinación.',
            ]);
        } catch (SamlConfigurationException) {
            return redirect()->route('login')->withErrors([
                'saml' => 'El acceso institucional no está configurado.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectAfterAuthentication($request, $user, $studentPracticeDestination);
    }

    public function metadata(SamlAuthService $saml): Response
    {
        try {
            $metadata = $saml->serviceProviderMetadata();
        } catch (SamlConfigurationException) {
            return response('SAML service provider metadata is unavailable.', 503);
        }

        return response($metadata, 200, [
            'Content-Type' => 'application/samlmetadata+xml',
        ]);
    }

    public function simulate(
        Request $request,
        SamlUserProvisioner $provisioner,
        StudentPracticeDestination $studentPracticeDestination,
    ): RedirectResponse {
        abort_unless(
            app()->environment(['local', 'testing']) && config('services.saml2.simulator_enabled'),
            404,
        );

        $emailAttribute = (string) config('services.saml2.email_attribute', 'uCorreo');
        $nameAttribute = (string) config('services.saml2.name_attribute', 'uNombre');
        $firstName = (string) config('services.saml2.simulator_firstname');
        $lastName = (string) config('services.saml2.simulator_lastname');

        try {
            $user = $provisioner->authenticateOrCreate([
                $emailAttribute => [(string) config('services.saml2.simulator_email')],
                $nameAttribute => [(string) config('services.saml2.simulator_name')],
                'sn' => [$lastName],
                'givenName' => [trim($firstName.' '.$lastName)],
                'displayName' => [$firstName],
            ]);
        } catch (InvalidSamlIdentityException) {
            return redirect()->route('login')->withErrors([
                'saml' => 'El simulador no tiene un correo de prueba válido configurado.',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectAfterAuthentication($request, $user, $studentPracticeDestination);
    }

    private function redirectAfterAuthentication(
        Request $request,
        User $user,
        StudentPracticeDestination $studentPracticeDestination,
    ): RedirectResponse {
        if ($user->role === UserRole::Student) {
            $request->session()->forget('url.intended');

            return redirect()->route($studentPracticeDestination->routeFor($user));
        }

        $destination = $user->role === UserRole::Coordinator
            ? route('coordinacion.practice-tracking.index')
            : route('home');

        return redirect()->intended($destination);
    }
}
