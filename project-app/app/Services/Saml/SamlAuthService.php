<?php

namespace App\Services\Saml;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Error;
use OneLogin\Saml2\Settings;
use OneLogin\Saml2\ValidationError;

class SamlAuthService
{
    public function initiateLogin(): RedirectResponse
    {
        if (! config('services.saml2.enabled')) {
            throw new SamlConfigurationException('Institutional SAML login is disabled.');
        }

        $relayState = bin2hex(random_bytes(32));

        try {
            $auth = new Auth($this->settings());
            $redirectUrl = $auth->login($relayState, [], false, false, true);
        } catch (Error|ValidationError $exception) {
            throw new SamlConfigurationException('The SAML service provider configuration is invalid.', 0, $exception);
        }

        $requestId = $auth->getLastRequestID();

        if (! is_string($redirectUrl) || $redirectUrl === '' || ! is_string($requestId) || $requestId === '') {
            throw new SamlConfigurationException('The SAML authentication request could not be created.');
        }

        Cache::put($this->requestCacheKey($relayState), $requestId, now()->addMinutes(5));

        return redirect()->away($redirectUrl);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function consumeResponse(mixed $relayState): array
    {
        if (! is_string($relayState) || preg_match('/\A[a-f0-9]{64}\z/D', $relayState) !== 1) {
            throw new SamlAuthenticationException('The SAML response has no valid relay state.');
        }

        $requestId = Cache::pull($this->requestCacheKey($relayState));

        if (! is_string($requestId) || $requestId === '') {
            throw new SamlAuthenticationException('The SAML response has no matching login request.');
        }

        try {
            $auth = new Auth($this->settings());
            $auth->processResponse($requestId);
        } catch (Error|ValidationError $exception) {
            throw new SamlAuthenticationException('The SAML response could not be validated.', 0, $exception);
        }

        if (! $auth->isAuthenticated() || $auth->getErrors() !== []) {
            throw new SamlAuthenticationException('The SAML response was rejected.');
        }

        return $auth->getAttributes();
    }

    public function serviceProviderMetadata(): string
    {
        try {
            return (new Settings($this->settings(spValidationOnly: true), true))->getSPMetadata();
        } catch (Error|ValidationError $exception) {
            throw new SamlConfigurationException('The SAML service provider metadata could not be generated.', 0, $exception);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function settings(bool $spValidationOnly = false): array
    {
        $config = [
            'strict' => true,
            'debug' => false,
            'sp' => [
                'entityId' => config('services.saml2.sp_entity_id') ?: route('auth.saml.metadata'),
                'assertionConsumerService' => [
                    'url' => route('auth.saml.acs'),
                ],
                'x509cert' => '',
                'privateKey' => '',
            ],
            'idp' => [
                'entityId' => config('services.saml2.idp_entity_id'),
                'singleSignOnService' => [
                    'url' => config('services.saml2.idp_sso_url'),
                ],
                'x509cert' => $this->certificate(),
            ],
            'security' => [
                'authnRequestsSigned' => false,
                'wantMessagesSigned' => false,
                'wantAssertionsSigned' => true,
                'wantAssertionsEncrypted' => false,
                'wantNameIdEncrypted' => false,
                'requestedAuthnContext' => false,
                'rejectUnsolicitedResponsesWithInResponseTo' => true,
                'destinationStrictlyMatches' => true,
                'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
                'digestAlgorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',
            ],
        ];

        if (! $spValidationOnly) {
            foreach ([
                'services.saml2.idp_entity_id',
                'services.saml2.idp_sso_url',
                'services.saml2.idp_x509_cert',
            ] as $key) {
                if (! is_string(config($key)) || trim(config($key)) === '') {
                    throw new SamlConfigurationException('The institutional SAML identity provider is not configured.');
                }
            }
        }

        try {
            new Settings($config, $spValidationOnly);
        } catch (Error|ValidationError $exception) {
            throw new SamlConfigurationException('The SAML service provider configuration is invalid.', 0, $exception);
        }

        return $config;
    }

    private function certificate(): string
    {
        $certificate = (string) config('services.saml2.idp_x509_cert', '');

        return str_replace('\\n', "\n", trim($certificate));
    }

    private function requestCacheKey(string $relayState): string
    {
        return 'saml2.authn_request.'.hash('sha256', $relayState);
    }
}
