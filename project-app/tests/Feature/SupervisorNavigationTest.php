<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_document_menu_uses_the_existing_supervisor_routes(): void
    {
        $company = Company::query()->create(['name' => 'Hotel Faro']);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($supervisor)
            ->get(route('supervisor.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Documentos');

        foreach ([
            'supervisor.practice-letters.index',
            'supervisor.practice-plans.index',
            'supervisor.practice-supervisor-evaluations.index',
            'supervisor.practice-surveys.index',
        ] as $routeName) {
            $response->assertSee('href="'.route($routeName).'"', false);
        }
    }

    public function test_supervisor_document_links_are_not_rendered_for_students(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);

        $this->actingAs($student)
            ->get(route('alumno.practice-tracking.index'))
            ->assertOk()
            ->assertDontSee('href="'.route('supervisor.practice-letters.index').'"', false)
            ->assertDontSee('href="'.route('supervisor.practice-plans.index').'"', false)
            ->assertDontSee('href="'.route('supervisor.practice-supervisor-evaluations.index').'"', false)
            ->assertDontSee('href="'.route('supervisor.practice-surveys.index').'"', false);
    }
}
