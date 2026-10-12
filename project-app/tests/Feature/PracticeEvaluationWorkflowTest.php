<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Livewire\Tutor\PracticeEvaluations\Manage;
use App\Models\Company;
use App\Models\PracticeEvaluation;
use App\Models\Rubric;
use App\Models\RubricVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class PracticeEvaluationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_tutor_cannot_start_until_a_tutor_rubric_is_published(): void
    {
        [$student, $tutor, , $request] = $this->createApprovedReport();
        $this->actingAs($tutor);

        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('startEvaluation')
            ->assertHasErrors(['evaluation']);

        $this->assertDatabaseCount('practice_evaluations', 0);
    }

    public function test_tutor_fixes_report_and_rubric_versions_and_submits_once_with_weighted_total(): void
    {
        [$student, $tutor, $coordinator, $request, $approvedReport] = $this->createApprovedReport();
        $rubricVersion = $this->publishTutorRubric();
        $this->actingAs($tutor);

        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('startEvaluation')
            ->assertHasNoErrors();
        $evaluation = PracticeEvaluation::query()->sole();
        $this->assertSame($approvedReport->id, $evaluation->practice_report_version_id);
        $this->assertSame($rubricVersion->id, $evaluation->rubric_version_id);
        $this->assertSame(PracticeEvaluationStatus::Draft, $evaluation->status);

        $component = Livewire::test(Manage::class, ['practiceRequest' => $request]);
        $criteria = $evaluation->criteria;
        $firstCriterion = $criteria[0];
        $secondCriterion = $criteria[1];
        $component->call('submit')->assertHasErrors(["scores.{$firstCriterion->id}"]);
        $component->set("scores.{$firstCriterion->id}", '1')
            ->set("scores.{$secondCriterion->id}", '2')
            ->call('submit')
            ->assertHasNoErrors();

        $evaluation->refresh();
        $this->assertSame(PracticeEvaluationStatus::Submitted, $evaluation->status);
        $this->assertSame(70.0, $evaluation->score_total);
        $this->assertNotNull($evaluation->submitted_at);
        $this->assertSame('Medio', $evaluation->criteria()->firstOrFail()->score_label);

        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('saveDraft')
            ->assertHasErrors('evaluation');
        $this->assertSame(1, PracticeEvaluation::query()->count());

        $this->actingAs($student)
            ->get(route('alumno.practice-evaluations.show', $evaluation))
            ->assertOk()
            ->assertSee('70.00 / 100');
        $this->actingAs($tutor)
            ->get(route('tutor.practice-evaluations.show', $evaluation))
            ->assertOk();
        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-evaluations.show', $evaluation))
            ->assertOk();
    }

    public function test_other_users_cannot_read_the_evaluation_and_unpublished_or_wrong_tutor_cannot_create_it(): void
    {
        [, $tutor, , $request] = $this->createApprovedReport();
        $this->publishTutorRubric();
        $otherTutor = User::factory()->create(['role' => UserRole::Tutor]);
        $this->actingAs($otherTutor);
        Livewire::test(Manage::class, ['practiceRequest' => $request])->assertNotFound();

        $this->actingAs($tutor);
        Livewire::test(Manage::class, ['practiceRequest' => $request])
            ->call('startEvaluation')
            ->assertHasNoErrors();
        $evaluation = PracticeEvaluation::query()->sole();

        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $this->actingAs($otherStudent)
            ->get(route('alumno.practice-evaluations.show', $evaluation))
            ->assertNotFound();
        $this->actingAs(User::factory()->create(['role' => UserRole::Supervisor]))
            ->get(route('tutor.practice-evaluations.show', $evaluation))
            ->assertForbidden();
    }

    private function createApprovedReport(): array
    {
        $company = Company::query()->create(['name' => 'Empresa de prueba']);
        $student = User::factory()->create(['role' => UserRole::Student]);
        $tutor = User::factory()->create(['role' => UserRole::Tutor]);
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
        ]);
        $request = $student->practiceRequests()->create([
            'tutor_id' => $tutor->id,
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
        $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => 'acceptances/demo.pdf',
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 8,
            'status' => PracticeAcceptanceStatus::Accepted,
            'submitted_at' => now(),
        ]);
        $plan = $request->practicePlan()->create([]);
        $plan->versions()->create([
            'version' => 1,
            'status' => PracticePlanStatus::Approved,
            'submitted_at' => now(),
        ]);
        $report = $request->practiceReport()->create([]);
        $approvedReport = $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::Approved,
            'company_name' => $company->name,
            'area' => 'Operaciones',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivo',
            'activities' => 'Actividades',
            'outcomes' => 'Resultados',
            'conclusions' => 'Conclusiones',
            'recommendations' => 'Recomendaciones',
            'reviewed_by' => $tutor->id,
            'reviewed_at' => now(),
            'submitted_at' => now(),
        ]);
        $report->update(['approved_version_id' => $approvedReport->id]);

        return [$student, $tutor, $coordinator, $request, $approvedReport];
    }

    private function publishTutorRubric(): RubricVersion
    {
        $rubric = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail();
        $version = $rubric->versions()->firstOrFail();
        $version->criteria()->createMany([
            [
                'name' => 'Desempeño',
                'description' => 'Criterio de prueba',
                'position' => 1,
                'weight' => 60,
                'scale' => [
                    ['value' => 0, 'label' => 'Bajo'],
                    ['value' => 1, 'label' => 'Medio'],
                    ['value' => 2, 'label' => 'Alto'],
                ],
            ],
            [
                'name' => 'Reporte',
                'description' => 'Criterio de reporte de prueba',
                'position' => 2,
                'weight' => 40,
                'scale' => [
                    ['value' => 0, 'label' => 'Insuficiente'],
                    ['value' => 1, 'label' => 'Suficiente'],
                    ['value' => 2, 'label' => 'Sobresaliente'],
                ],
            ],
        ]);
        $version->publish();

        return $version;
    }
}
