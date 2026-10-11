<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRoleMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_coordinator_flag_is_migrated_to_the_coordinator_role(): void
    {
        $this->artisan('migrate:rollback', [
            '--path' => 'database/migrations/2026_10_10_000003_migrate_users_to_single_role.php',
            '--force' => true,
        ])->assertExitCode(0);

        DB::table('users')->insert([
            'name' => 'Legacy coordinator',
            'email' => 'legacy-coordinator@example.test',
            'password' => Hash::make('password'),
            'is_coordinator' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('migrate', [
            '--force' => true,
        ])->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'legacy-coordinator@example.test',
            'role' => 'coordinator',
        ]);
    }
}
