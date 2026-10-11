<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateCoordinatorCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_creates_a_coordinator_without_printing_or_storing_plaintext_password(): void
    {
        $this->artisan('app:create-coordinator')
            ->expectsQuestion('Nombre', 'Coordinación')
            ->expectsQuestion('Email', 'coordinacion@example.test')
            ->expectsQuestion('Contraseña', 'TestPassword!123')
            ->expectsQuestion('Confirmar contraseña', 'TestPassword!123')
            ->expectsOutput('La cuenta de Coordinación fue creada.')
            ->assertExitCode(0);

        $user = User::query()->where('email', 'coordinacion@example.test')->firstOrFail();

        $this->assertTrue($user->isCoordinator());
        $this->assertTrue(Hash::check('TestPassword!123', $user->password));
        $this->assertNotSame('TestPassword!123', $user->password);
    }

    public function test_seeder_does_not_create_default_login_credentials(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 0);
    }
}
