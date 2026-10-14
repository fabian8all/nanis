<?php

namespace Tests\Feature;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeEvaluation;
use App\Models\PracticeLetter;
use App\Models\PracticeSupervisorEvaluation;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PracticeRecordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_student_owner_and_coordinator_can_open_an_incomplete_record_with_each_pending_requirement(): void
    {
        [$student, , , $coordinator, $request] = $this->createPractice();
        $url = route('alumno.practice-records.show', $request);

        $this->actingAs($student)
            ->get($url)
            ->assertOk()
            ->assertSee('Expediente de práctica')
            ->assertSee('Incompleto')
            ->assertSee('Carta de término emitida')
            ->assertSee('Reporte final aprobado')
            ->assertSee('Evaluación terminal del Tutor enviada')
            ->assertSee('Evaluación terminal del Supervisor enviada')
            ->assertSeeInOrder([
                'id="presentation-letter-heading"',
                'id="acceptance-letter-heading"',
                'id="practice-plan-heading"',
                'id="approved-report-heading"',
                'id="supervisor-evaluation-heading"',
                'id="tutor-evaluation-heading"',
                'id="termination-letter-heading"',
            ], false)
            ->assertSee('data-document-status="No emitida"', false)
            ->assertSee('data-document-status="No entregada"', false)
            ->assertSee('data-document-status="No entregado"', false)
            ->assertSee('Registrar plan de trabajo');

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Incompleto')
            ->assertDontSee(route('alumno.practice-reports.show', $request));

        $this->actingAs($student)
            ->get(route('alumno.practice-tracking.index'))
            ->assertOk()
            ->assertSee(route('alumno.practice-records.show', $request));

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertOk()
            ->assertSee(route('coordinacion.practice-records.show', $request));
    }

    public function test_pending_documents_show_delivery_and_review_badges(): void
    {
        [$student, , $supervisor, , $request] = $this->createPractice();
        $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "practice-acceptances/{$request->id}/pending.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => 'pending_review',
            'submitted_at' => now(),
        ]);
        $plan = $request->practicePlan()->create([]);
        $plan->versions()->create([
            'version' => 1,
            'status' => PracticePlanStatus::PendingSupervisorReview,
            'submitted_at' => now(),
        ]);
        $report = $request->practiceReport()->create([]);
        $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::PendingTutorReview,
            'company_name' => $request->company->name,
            'area' => 'Operaciones',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivo',
            'activities' => 'Actividades',
            'outcomes' => 'Resultados',
            'conclusions' => 'Conclusiones',
            'recommendations' => 'Recomendaciones',
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($student)
            ->get(route('alumno.practice-records.show', $request))
            ->assertOk()
            ->assertSee('No hay una carta de aceptación aprobada.')
            ->assertDontSee('Consultar carta de aceptación')
            ->assertSee('Consultar plan de trabajo')
            ->assertSee(route('alumno.practice-reports.show', $request));

        $this->assertSame(3, substr_count($response->getContent(), 'data-document-status="En revisión"'));
    }

    public function test_only_the_owner_student_assigned_tutor_and_coordinator_can_open_a_record(): void
    {
        [$student, $tutor, $supervisor, $coordinator, $request] = $this->createPractice();
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $otherTutor = User::factory()->create(['role' => UserRole::Tutor]);

        $this->actingAs($otherStudent)
            ->get(route('alumno.practice-records.show', $request))
            ->assertNotFound();

        $this->actingAs($tutor)
            ->get(route('tutor.practice-records.show', $request))
            ->assertOk();
        $this->actingAs($otherTutor)
            ->get(route('tutor.practice-records.show', $request))
            ->assertNotFound();

        $this->actingAs($supervisor)
            ->get(route('coordinacion.practice-records.show', $request))
            ->assertForbidden();

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-records.show', $request))
            ->assertOk();

        $this->actingAs($student)
            ->get(route('alumno.practice-records.show', $request))
            ->assertOk();
    }

    public function test_complete_record_shows_only_approved_report_and_accepted_letter_with_private_links(): void
    {
        Storage::fake('local');
        [$student, $tutor, $supervisor, $coordinator, $request] = $this->createPractice();
        $acceptancePath = "practice-acceptances/{$request->id}/acceptance.pdf";
        Storage::disk('local')->put($acceptancePath, 'accepted letter');
        $acceptance = $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => $acceptancePath,
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);
        $practiceLetter = PracticeLetter::query()->create([
            'practice_request_id' => $request->id,
            'coordinator_id' => $coordinator->id,
            'folio_year' => 2026,
            'folio_number' => $request->id,
            'folio' => "CP-2026-{$request->id}",
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'practice_location' => 'Colima',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'issued_at' => now(),
        ]);

        $issuance = $request->terminationLetterIssuance()->create([
            'issued_by' => $coordinator->id,
            'issued_at' => now(),
        ]);
        $request->update(['status' => PracticeRequestStatus::Completed]);

        $plan = $request->practicePlan()->create([]);
        $plan->versions()->create([
            'version' => 1,
            'status' => PracticePlanStatus::Approved,
            'submitted_at' => now(),
        ]);
        $report = $request->practiceReport()->create([]);
        $reportVersion = $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::Approved,
            'company_name' => $request->company->name,
            'area' => 'Operaciones',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivo documentado',
            'activities' => 'Actividades realizadas',
            'outcomes' => 'Resultados obtenidos',
            'conclusions' => 'Conclusiones',
            'recommendations' => 'Recomendaciones',
            'submitted_at' => now(),
            'reviewed_by' => $tutor->id,
            'reviewed_at' => now(),
        ]);
        $report->update(['approved_version_id' => $reportVersion->id]);

        $attachmentPath = "practice-reports/{$request->id}/evidencia.pdf";
        Storage::disk('local')->put($attachmentPath, 'anexo');
        $attachment = $reportVersion->attachments()->create([
            'file_path' => $attachmentPath,
            'original_name' => 'evidencia.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 5,
        ]);

        $tutorRubric = Rubric::query()->where('type', RubricType::Tutor->value)->firstOrFail()->versions()->firstOrFail();
        $supervisorRubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail()->versions()->firstOrFail();
        $tutorEvaluation = PracticeEvaluation::query()->create([
            'practice_request_id' => $request->id,
            'tutor_id' => $tutor->id,
            'practice_report_version_id' => $reportVersion->id,
            'rubric_version_id' => $tutorRubric->id,
            'status' => PracticeEvaluationStatus::Submitted,
            'score_total' => 88,
            'submitted_at' => now(),
        ]);
        $supervisorEvaluation = PracticeSupervisorEvaluation::query()->create([
            'practice_request_id' => $request->id,
            'practice_acceptance_submission_id' => $acceptance->id,
            'supervisor_id' => $supervisor->id,
            'rubric_version_id' => $supervisorRubric->id,
            'status' => PracticeEvaluationStatus::Submitted,
            'score_total' => 92,
            'submitted_at' => now(),
        ]);

        $this->actingAs($student)
            ->get(route('alumno.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Completo')
            ->assertSee('data-document-status="Emitida"', false)
            ->assertSee('data-document-status="Aprobada"', false)
            ->assertSee('data-document-status="Aprobado"', false)
            ->assertSee('data-document-status="Enviada"', false)
            ->assertSee(route('practice-termination-letters.print', $issuance))
            ->assertSee('Objetivo documentado')
            ->assertSee('evidencia.pdf')
            ->assertSee(route('alumno.practice-evaluations.show', $tutorEvaluation))
            ->assertSee(route('alumno.practice-supervisor-evaluations.show', $supervisorEvaluation));

        $this->get(route('practice-termination-letters.print', $issuance))->assertOk();
        $this->get(route('practice-reports.attachments.download', $attachment))->assertOk();
        $this->get(route('alumno.practice-acceptances.file', $acceptance))->assertOk();

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Completo')
            ->assertSee(route('coordinacion.practice-evaluations.show', $tutorEvaluation))
            ->assertSee(route('coordinacion.practice-supervisor-evaluations.show', $supervisorEvaluation));
        $this->get(route('coordinacion.practice-acceptances.file', $acceptance))->assertOk();

        $this->actingAs($tutor)
            ->get(route('tutor.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Completo')
            ->assertSee('Carta de presentación')
            ->assertSee('Carta de aceptación')
            ->assertSee('Plan de trabajo')
            ->assertSee(route('tutor.practice-letters.show', $request->letter))
            ->assertSee(route('tutor.practice-acceptances.file', $acceptance))
            ->assertSee(route('practice-termination-letters.print', $issuance))
            ->assertSee(route('tutor.practice-supervisor-evaluations.show', $supervisorEvaluation));
        $this->get(route('tutor.practice-letters.show', $request->letter))
            ->assertOk()
            ->assertSee('href="'.route('tutor.practice-records.show', $request).'"', false);
        $this->get(route('tutor.practice-acceptances.file', $acceptance))->assertOk();
    }

    public function test_assigned_supervisor_sees_only_their_four_allowed_documents(): void
    {
        Storage::fake('local');
        [$student, $tutor, $supervisor, $coordinator, $request] = $this->createPractice();
        $acceptancePath = "practice-acceptances/{$request->id}/acceptance.pdf";
        Storage::disk('local')->put($acceptancePath, 'acceptance');
        $acceptance = $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => $acceptancePath,
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);
        $practiceLetter = PracticeLetter::query()->create([
            'practice_request_id' => $request->id,
            'coordinator_id' => $coordinator->id,
            'folio_year' => 2026,
            'folio_number' => $request->id,
            'folio' => "CP-2026-{$request->id}",
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'practice_location' => 'Colima',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'issued_at' => now(),
        ]);
        $report = $request->practiceReport()->create([]);
        $approvedVersion = $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::Approved,
            'company_name' => $request->company->name,
            'area' => 'Operaciones',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivo compartido',
            'activities' => 'Actividades compartidas',
            'outcomes' => 'Resultados compartidos',
            'conclusions' => 'Conclusiones compartidas',
            'recommendations' => 'Recomendaciones compartidas',
            'submitted_at' => now(),
            'reviewed_by' => $tutor->id,
            'reviewed_at' => now(),
        ]);
        $report->update(['approved_version_id' => $approvedVersion->id]);
        $attachmentPath = "practice-reports/{$request->id}/approved.pdf";
        Storage::disk('local')->put($attachmentPath, 'approved report attachment');
        $attachment = $approvedVersion->attachments()->create([
            'file_path' => $attachmentPath,
            'original_name' => 'reporte.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
        ]);
        $supervisorRubric = Rubric::query()->where('type', RubricType::Supervisor->value)->firstOrFail()->versions()->firstOrFail();
        $evaluation = $request->practiceSupervisorEvaluation()->create([
            'practice_acceptance_submission_id' => $acceptance->id,
            'supervisor_id' => $supervisor->id,
            'rubric_version_id' => $supervisorRubric->id,
            'status' => PracticeEvaluationStatus::Submitted,
            'score_total' => 91,
            'submitted_at' => now(),
        ]);

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Carta de presentación')
            ->assertSee('Carta de aceptación')
            ->assertSee('Reporte final del alumno')
            ->assertSee('Objetivo compartido')
            ->assertSee('Tu evaluación')
            ->assertSee('data-document-status="Aprobada"', false)
            ->assertSee('data-document-status="Aprobado"', false)
            ->assertSee('data-document-status="Enviada"', false)
            ->assertDontSee('id="practice-plan-heading"', false)
            ->assertDontSee('Requisitos de cierre')
            ->assertDontSee('Evaluación terminal del Tutor')
            ->assertDontSee('Carta de término');

        $this->get(route('supervisor.practice-letters.show', $practiceLetter))->assertOk();
        $this->get(route('supervisor.practice-acceptances.file', $acceptance))->assertOk();
        $this->get(route('practice-reports.attachments.download', $attachment))->assertOk();
        $this->get(route('supervisor.practice-supervisor-evaluations.show', $evaluation))->assertOk();

        $otherSupervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $request->company_id,
            'email_verified_at' => now(),
        ]);
        $this->actingAs($otherSupervisor)
            ->get(route('supervisor.practice-records.show', $request))
            ->assertNotFound();
        $this->get(route('supervisor.practice-acceptances.file', $acceptance))->assertForbidden();
        $this->get(route('practice-reports.attachments.download', $attachment))->assertForbidden();

        $misassignedSupervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => Company::query()->create(['name' => 'Empresa no asignada'])->id,
            'email_verified_at' => now(),
        ]);
        $request->acceptanceSubmissions()->create([
            'supervisor_id' => $misassignedSupervisor->id,
            'version' => 2,
            'file_path' => "practice-acceptances/{$request->id}/misassigned.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);
        $this->actingAs($misassignedSupervisor)
            ->get(route('supervisor.practice-records.show', $request))
            ->assertNotFound();
    }

    private function createPractice(): array
    {
        $company = Company::query()->create(['name' => 'Empresa de prueba']);
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
            'start_date' => '2026-11-01',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2027-01-31',
            'status' => PracticeRequestStatus::Approved,
            'non_responsiva_accepted_at' => now(),
        ]);

        return [$student, $tutor, $supervisor, $coordinator, $request];
    }
}
