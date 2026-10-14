<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticePlans\Review as CoordinatorReview;
use App\Livewire\Student\PracticePlans\Show as StudentPlan;
use App\Livewire\Supervisor\PracticePlans\Review as SupervisorReview;
use App\Models\Company;
use App\Models\PracticePlan;
use App\Models\PracticePlanVersion;
use App\Models\PracticeRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PracticePlanWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_plan_only_after_company_acceptance_is_approved(): void
    {
        [$student, $supervisor, $coordinator, $request, $letter, $acceptance] = $this->createPractice();

        $this->actingAs($student)
            ->get(route('alumno.practice-plans.show', $request))
            ->assertNotFound();

        $acceptance->update(['status' => PracticeAcceptanceStatus::Accepted]);
        $this->get(route('alumno.practice-plans.show', $request))
            ->assertOk()
            ->assertSee('Plan de trabajo');

        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->set('activities', [$this->activity([
                'start_date' => '2026-01-10',
                'end_date' => '2026-01-09',
            ])])
            ->call('submit')
            ->assertHasErrors(['activities.0.end_date']);

        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->set('activities', [$this->activity([
                'start_date' => '2026-01-10',
                'end_date' => '2026-01-11',
            ])])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('alumno.practice-plans.show', $request));

        $plan = PracticePlan::query()->sole();
        $version = $plan->versions()->sole();
        $this->assertSame(PracticePlanStatus::PendingSupervisorReview, $version->status);
        $this->assertDatabaseHas('practice_plan_activities', [
            'practice_plan_version_id' => $version->id,
            'responsible' => 'Ana Supervisora',
            'expected_result' => 'Inventario de servicios actualizado',
        ]);

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-plans.index'))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee('Revisar plan');

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-plans.index'))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee('Pendiente de revisión por el Supervisor')
            ->assertDontSee('pending_supervisor_review');

        $this->actingAs(User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => Company::query()->create(['name' => 'Otra Empresa'])->id,
        ]))
            ->get(route('supervisor.practice-plans.review', $version))
            ->assertNotFound();
    }

    public function test_supervisor_must_comment_when_returning_and_student_resubmission_keeps_old_version(): void
    {
        [$student, $supervisor, , $request] = $this->createPractice(accepted: true);
        $version = $this->submitPlan($student, $request);
        $this->actingAs($supervisor);

        Livewire::test(SupervisorReview::class, ['practicePlanVersion' => $version])
            ->call('returnForChanges')
            ->assertHasErrors(['reviewComment' => 'required']);

        Livewire::test(SupervisorReview::class, ['practicePlanVersion' => $version])
            ->set('reviewComment', 'Ajusta las fechas con el área asignada.')
            ->call('returnForChanges')
            ->assertHasNoErrors()
            ->assertRedirect(route('supervisor.practice-plans.index'));

        $this->assertSame(PracticePlanStatus::ReturnedBySupervisor, $version->fresh()->status);
        $this->assertSame('Ajusta las fechas con el área asignada.', $version->fresh()->supervisor_review_comment);

        $this->actingAs($student);
        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->assertSet('activities.0.description', 'Apoyar operación turística')
            ->set('activities.0.description', 'Apoyar operación turística corregida')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('practice_plan_versions', 2);
        $this->assertSame(PracticePlanStatus::ReturnedBySupervisor, $version->fresh()->status);
        $newVersion = PracticePlanVersion::query()->where('version', 2)->firstOrFail();
        $this->assertSame(PracticePlanStatus::PendingSupervisorReview, $newVersion->status);
        $this->assertSame('Ajusta las fechas con el área asignada.', $version->fresh()->supervisor_review_comment);
        $this->assertSame('Apoyar operación turística', $version->activities()->firstOrFail()->description);
    }

    public function test_coordinator_approval_completes_plan_and_preserves_review_history(): void
    {
        [$student, $supervisor, $coordinator, $request] = $this->createPractice(accepted: true);
        $version = $this->submitPlan($student, $request);
        $this->actingAs($supervisor);
        Livewire::test(SupervisorReview::class, ['practicePlanVersion' => $version])
            ->call('approve')
            ->assertHasNoErrors();

        $this->actingAs($coordinator);
        Livewire::test(CoordinatorReview::class, ['practicePlanVersion' => $version])
            ->call('approve')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.practice-plans.index'));

        $version->refresh();
        $this->assertSame(PracticePlanStatus::Approved, $version->status);
        $this->assertSame($supervisor->id, $version->supervisor_reviewed_by);
        $this->assertSame($coordinator->id, $version->coordinator_reviewed_by);

        $this->actingAs($student);
        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->assertSee('Coordinación aprobó tu plan de trabajo.')
            ->assertDontSee('Enviar al supervisor');

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-plans.review', $version))
            ->assertOk()
            ->assertSee('Historial')
            ->assertSee('Esta versión no está pendiente de revisión');

        $this->assertSame(1, PracticePlanVersion::query()->count());
    }

    public function test_coordinator_return_routes_corrected_plan_back_to_supervisor(): void
    {
        [$student, $supervisor, $coordinator, $request] = $this->createPractice(accepted: true);
        $version = $this->submitPlan($student, $request);
        $this->actingAs($supervisor);
        Livewire::test(SupervisorReview::class, ['practicePlanVersion' => $version])
            ->call('approve');

        $this->actingAs($coordinator);
        Livewire::test(CoordinatorReview::class, ['practicePlanVersion' => $version])
            ->call('returnForChanges')
            ->assertHasErrors(['reviewComment' => 'required']);

        Livewire::test(CoordinatorReview::class, ['practicePlanVersion' => $version])
            ->set('reviewComment', 'Incluye un resultado medible.')
            ->call('returnForChanges')
            ->assertHasNoErrors();
        $this->assertSame(PracticePlanStatus::ReturnedByCoordinator, $version->fresh()->status);

        $this->actingAs($student);
        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->set('activities.0.expected_result', 'Registro semanal de servicios entregados')
            ->call('submit')
            ->assertHasNoErrors();

        $newVersion = PracticePlanVersion::query()->where('version', 2)->firstOrFail();
        $this->assertSame(PracticePlanStatus::PendingSupervisorReview, $newVersion->status);
        $this->assertSame('Incluye un resultado medible.', $version->fresh()->coordinator_review_comment);
    }

    private function createPractice(bool $accepted = false): array
    {
        $company = Company::query()->create(['name' => 'Hotel de Prueba']);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
        ]);
        $student = User::factory()->create(['role' => UserRole::Student]);
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $request = $student->practiceRequests()->create([
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
            'status' => PracticeRequestStatus::Approved,
            'non_responsiva_accepted_at' => now(),
        ]);
        $letter = $request->letter()->create([
            'coordinator_id' => $coordinator->id,
            'folio_year' => 2026,
            'folio_number' => 1,
            'folio' => 'CP-2026-0001',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'practice_location' => 'Colima',
            'start_date' => '2026-11-01',
            'end_date' => '2027-01-31',
            'issued_at' => now(),
        ]);
        $acceptance = $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "practice-acceptances/{$request->id}/acceptance.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 8,
            'status' => $accepted
                ? PracticeAcceptanceStatus::Accepted
                : PracticeAcceptanceStatus::PendingReview,
            'submitted_at' => now(),
        ]);

        return [$student, $supervisor, $coordinator, $request, $letter, $acceptance];
    }

    private function submitPlan(User $student, PracticeRequest $request): PracticePlanVersion
    {
        $this->actingAs($student);
        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->set('activities', [$this->activity()])
            ->call('submit')
            ->assertHasNoErrors();

        return PracticePlanVersion::query()->sole();
    }

    private function activity(array $overrides = []): array
    {
        return array_merge([
            'description' => 'Apoyar operación turística',
            'responsible' => 'Ana Supervisora',
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-15',
            'expected_result' => 'Inventario de servicios actualizado',
        ], $overrides);
    }
}
