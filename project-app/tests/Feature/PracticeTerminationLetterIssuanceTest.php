<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticeTerminationLetters\Index as CoordinatorLetterQueue;
use App\Livewire\PracticeRecords\Show as PracticeRecord;
use App\Models\Company;
use App\Models\PracticeEvaluation;
use App\Models\PracticeRequest;
use App\Models\PracticeSupervisorEvaluation;
use App\Models\PracticeTerminationLetterIssuance;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Tests\TestCase;

class PracticeTerminationLetterIssuanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_coordinator_can_issue_printable_letter_only_after_report_and_both_evaluations_are_submitted(): void
    {
        [$coordinator, , , $request] = $this->createPractice();

        $this->actingAs($coordinator);
        Livewire::test(CoordinatorLetterQueue::class)
            ->assertSee('No hay expedientes pendientes de emisión de carta.');

        Livewire::test(PracticeRecord::class, ['practiceRequest' => $request])
            ->call('issueTerminationLetter')
            ->assertHasErrors('issuance');
        $this->assertDatabaseCount('practice_termination_letter_issuances', 0);
        $this->assertSame(PracticeRequestStatus::Approved, $request->fresh()->status);

        $this->addClosureEvidence($request);

        Livewire::test(CoordinatorLetterQueue::class)
            ->assertSee($request->student->name)
            ->assertSee('Revisar expediente');

        Livewire::test(PracticeRecord::class, ['practiceRequest' => $request])
            ->call('issueTerminationLetter')
            ->assertRedirect(route(
                'practice-termination-letters.print',
                PracticeTerminationLetterIssuance::query()->sole(),
            ));

        $issuance = PracticeTerminationLetterIssuance::query()->sole();
        $this->assertSame($coordinator->id, $issuance->issued_by);
        $this->assertSame(PracticeRequestStatus::Completed, $request->fresh()->status);

        Livewire::test(PracticeRecord::class, ['practiceRequest' => $request->fresh()])
            ->call('issueTerminationLetter')
            ->assertRedirect(route('practice-termination-letters.print', $issuance));
        $this->assertDatabaseCount('practice_termination_letter_issuances', 1);
    }

    public function test_printable_letter_uses_240_hours_and_is_available_only_to_student_tutor_and_coordinator(): void
    {
        [$coordinator, $student, $tutor, $request] = $this->createPractice();
        $this->addClosureEvidence($request);
        $issuance = $request->terminationLetterIssuance()->create([
            'issued_by' => $coordinator->id,
            'issued_at' => now(),
        ]);
        $request->update(['status' => PracticeRequestStatus::Completed]);

        foreach ([$coordinator, $student, $tutor] as $viewer) {
            $this->actingAs($viewer)
                ->get(route('practice-termination-letters.print', $issuance))
                ->assertOk()
                ->assertSee('240 horas')
                ->assertSee('firma')
                ->assertSee('Sello')
                ->assertDontSee('400 horas')
                ->assertDontSee('NOMBRE DEL/LA RESPONSABLE');
        }

        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $otherTutor = User::factory()->create(['role' => UserRole::Tutor]);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $request->company_id,
            'email_verified_at' => now(),
        ]);

        foreach ([$otherStudent, $otherTutor, $supervisor] as $viewer) {
            $this->actingAs($viewer)
                ->get(route('practice-termination-letters.print', $issuance))
                ->assertNotFound();
        }

        $this->expectException(\LogicException::class);
        $issuance->update(['issued_at' => now()->addDay()]);
    }

    public function test_old_student_upload_route_is_not_exposed_anymore(): void
    {
        $this->assertFalse(Route::has('alumno.practice-termination-letters.upload'));
        $this->assertFalse(Route::has('coordinacion.practice-termination-letters.review'));
        $this->assertFalse(Route::has('alumno.practice-termination-letters.file'));
    }

    private function createPractice(): array
    {
        $company = Company::query()->create(['name' => 'Unidad receptora de prueba']);
        $student = User::factory()->create(['role' => UserRole::Student]);
        $tutor = User::factory()->create(['role' => UserRole::Tutor]);
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $company->id,
            'email_verified_at' => now(),
        ]);
        $request = $student->practiceRequests()->create([
            'tutor_id' => $tutor->id,
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-01-10',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2026-06-10',
            'status' => PracticeRequestStatus::Approved,
            'non_responsiva_accepted_at' => now(),
        ]);
        $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "practice-acceptances/{$request->id}/acceptance.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => PracticeAcceptanceStatus::Accepted,
            'submitted_at' => now(),
        ]);

        return [$coordinator, $student, $tutor, $request];
    }

    private function addClosureEvidence(PracticeRequest $request): void
    {
        $tutor = $request->tutor;
        $report = $request->practiceReport()->create([]);
        $reportVersion = $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::Approved,
            'company_name' => $request->company->name,
            'area' => 'Operaciones',
            'period' => 'Enero a junio',
            'objectives' => 'Atender a visitantes',
            'activities' => 'Atención y apoyo',
            'outcomes' => 'Resultados documentados',
            'conclusions' => 'Conclusiones documentadas',
            'recommendations' => 'Recomendaciones documentadas',
            'submitted_at' => now(),
            'reviewed_by' => $tutor->id,
            'reviewed_at' => now(),
        ]);
        $report->update(['approved_version_id' => $reportVersion->id]);
        $tutorRubric = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail()->versions()->firstOrFail();
        $supervisorRubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail()->versions()->firstOrFail();
        PracticeEvaluation::query()->create([
            'practice_request_id' => $request->id,
            'tutor_id' => $tutor->id,
            'practice_report_version_id' => $reportVersion->id,
            'rubric_version_id' => $tutorRubric->id,
            'status' => PracticeEvaluationStatus::Submitted,
            'score_total' => 86,
            'submitted_at' => now(),
        ]);
        PracticeSupervisorEvaluation::query()->create([
            'practice_request_id' => $request->id,
            'practice_acceptance_submission_id' => $request->latestAcceptanceSubmission->id,
            'supervisor_id' => $request->latestAcceptanceSubmission->supervisor_id,
            'rubric_version_id' => $supervisorRubric->id,
            'status' => PracticeEvaluationStatus::Submitted,
            'score_total' => 90,
            'submitted_at' => now(),
        ]);
    }
}
