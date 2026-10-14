<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoordinatorNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_navigation_exposes_catalog_documents_and_rubrics_routes(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);

        $response = $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Catálogo')
            ->assertSee('Seguimiento')
            ->assertSee('Documentos')
            ->assertSee('Rúbricas');

        foreach ([
            'coordinacion.companies.index',
            'coordinacion.practice-requests.index',
            'coordinacion.practice-acceptances.index',
            'coordinacion.practice-termination-letters.index',
            'coordinacion.practice-plans.index',
            'coordinacion.practice-reports.index',
            'coordinacion.practice-evaluations.index',
            'coordinacion.practice-supervisor-evaluations.index',
            'coordinacion.practice-surveys.manage',
            'coordinacion.rubrics.manage',
        ] as $routeName) {
            $response->assertSee('href="'.route($routeName).'"', false);
        }
    }

    public function test_coordinator_navigation_is_not_rendered_for_students(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);

        $this->actingAs($student)
            ->get(route('alumno.practice-tracking.index'))
            ->assertOk()
            ->assertDontSee('Documentos')
            ->assertDontSee('Rúbricas')
            ->assertDontSee('href="'.route('coordinacion.companies.index').'"', false);
    }
}
