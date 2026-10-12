<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Livewire\Student\PracticePlans\Show as StudentPlan;
use App\Livewire\Supervisor\PracticeEvaluations\Manage;
use App\Models\Company;
use App\Models\PracticeSupervisorEvaluation;
use App\Models\Rubric;
use App\Models\RubricVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class PracticeSupervisorEvaluationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_supervisor_cannot_start_until_the_supervisor_rubric_is_published(): void
    {
        [, $supervisor, , $request] = $this->createEligiblePractice();
        $this->actingAs($supervisor);

        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('startEvaluation')
            ->assertHasErrors(['evaluation']);

        $this->assertDatabaseCount('practice_supervisor_evaluations', 0);
    }

    public function test_evaluation_fixes_acceptance_and_rubric_versions_and_submits_immutable_weighted_result(): void
    {
        [$student, $supervisor, $coordinator, $request, $acceptance] = $this->createEligiblePractice();
        $rubricVersion = $this->publishSupervisorRubric();
        $this->actingAs($supervisor);

        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('startEvaluation')
            ->assertHasNoErrors();
        $evaluation = PracticeSupervisorEvaluation::query()->sole();
        $this->assertSame($acceptance->id, $evaluation->practice_acceptance_submission_id);
        $this->assertSame($supervisor->id, $evaluation->supervisor_id);
        $this->assertSame($rubricVersion->id, $evaluation->rubric_version_id);
        $this->assertSame(PracticeEvaluationStatus::Draft, $evaluation->status);

        $criteria = $evaluation->criteria;
        $component = Livewire::test(Manage::class, ['practiceRequest' => $request]);
        $component->call('submit')->assertHasErrors(["scores.{$criteria[0]->id}"]);
        $component->set("scores.{$criteria[0]->id}", '1')
            ->set("scores.{$criteria[1]->id}", '2')
            ->call('submit')
            ->assertHasNoErrors();

        $evaluation->refresh();
        $this->assertSame(PracticeEvaluationStatus::Submitted, $evaluation->status);
        $this->assertSame(75.0, $evaluation->score_total);
        $this->assertNotNull($evaluation->submitted_at);
        $this->assertSame('Adecuado', $evaluation->criteria()->firstOrFail()->score_label);

        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('saveDraft')
            ->assertHasErrors('evaluation');
        $this->assertSame(1, PracticeSupervisorEvaluation::query()->count());

        $this->actingAs($student)
            ->get(route('alumno.practice-supervisor-evaluations.show', $evaluation))
            ->assertOk()
            ->assertSee('75.00 / 100');
        Livewire::test(StudentPlan::class, ['practiceRequest' => $request])
            ->assertSee('Consultar evaluación final del supervisor');
        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-supervisor-evaluations.show', $evaluation))
            ->assertOk();
        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-supervisor-evaluations.show', $evaluation))
            ->assertOk();
    }

    public function test_only_supervisor_who_submitted_accepted_company_letter_can_evaluate_or_view_result(): void
    {
        [$student, $supervisor, , $request] = $this->createEligiblePractice();
        $otherSupervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => Company::query()->create(['name' => 'Otra empresa'])->id,
            'email_verified_at' => now(),
        ]);
        $this->actingAs($otherSupervisor);
        Livewire::test(Manage::class, ['practiceRequest' => $request])->assertNotFound();
        $this->get(route('supervisor.practice-supervisor-evaluations.index'))
            ->assertOk()
            ->assertDontSee($student->name);

        $this->publishSupervisorRubric();
        $this->actingAs($supervisor);
        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('startEvaluation')
            ->assertHasNoErrors();
        $evaluation = PracticeSupervisorEvaluation::query()->sole();

        $this->actingAs($otherSupervisor)
            ->get(route('supervisor.practice-supervisor-evaluations.show', $evaluation))
            ->assertNotFound();
    }

    public function test_unapproved_plan_and_wrong_role_cannot_access_evaluation(): void
    {
        [, $supervisor, , $request] = $this->createEligiblePractice(planStatus: PracticePlanStatus::PendingCoordinatorReview);
        $this->actingAs($supervisor);
        $this->get(route('supervisor.practice-supervisor-evaluations.manage', $request))->assertNotFound();

        $this->actingAs(User::factory()->create(['role' => UserRole::Student]))
            ->get(route('supervisor.practice-supervisor-evaluations.index'))
            ->assertForbidden();
    }

    private function createEligiblePractice(
        ?User $student = null,
        PracticePlanStatus $planStatus = PracticePlanStatus::Approved,
    ): array {
        $company = Company::query()->create(['name' => 'Empresa de prueba']);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);
        $student ??= User::factory()->create(['role' => UserRole::Student]);
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
        $acceptance = $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "acceptances/{$request->id}.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 8,
            'status' => PracticeAcceptanceStatus::Accepted,
            'reviewed_by' => $coordinator->id,
            'reviewed_at' => now(),
            'submitted_at' => now(),
        ]);
        $plan = $request->practicePlan()->create([]);
        $plan->versions()->create([
            'version' => 1,
            'status' => $planStatus,
            'submitted_at' => now(),
        ]);

        return [$student, $supervisor, $coordinator, $request, $acceptance];
    }

    private function publishSupervisorRubric(): RubricVersion
    {
        $rubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail();
        $version = $rubric->versions()->firstOrFail();
        $version->criteria()->where('position', '>', 2)->delete();
        $version->criteria()->update([
            'weight' => 50,
            'scale' => [
                ['value' => 0, 'label' => 'En desarrollo'],
                ['value' => 1, 'label' => 'Adecuado'],
                ['value' => 2, 'label' => 'Destacado'],
            ],
        ]);
        $version->publish();

        return $version;
    }
}
