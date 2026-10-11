<?php

namespace App\Services\Saml;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Str;

class SamlUserProvisioner
{
    /**
     * @param  array<string, array<int, mixed>>  $attributes
     */
    public function authenticateOrCreate(array $attributes): User
    {
        $email = $this->firstString(
            $attributes,
            (string) config('services.saml2.email_attribute', 'uCorreo'),
        );

        if ($email === null || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidSamlIdentityException('The SAML identity has no valid institutional email.');
        }

        $email = mb_strtolower(trim($email));
        $name = $this->firstString(
            $attributes,
            (string) config('services.saml2.name_attribute', 'uNombre'),
        );

        return User::unguarded(fn (): User => User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => $name ?? $email,
                'email_verified_at' => now(),
                'password' => Str::random(64),
                'role' => UserRole::Student,
            ],
        ));
    }

    /**
     * @param  array<string, array<int, mixed>>  $attributes
     */
    private function firstString(array $attributes, string $attribute): ?string
    {
        $value = $attributes[$attribute][0] ?? null;

        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
