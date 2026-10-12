<?php

namespace Tests\Feature;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\PracticeTerminationLetterStatus;
use App\Enums\RubricType;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeEvaluation;
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
            ->assertSee('Carta de término aceptada')
            ->assertSee('Reporte final aprobado')
            ->assertSee('Evaluación terminal del Tutor enviada')
            ->assertSee('Evaluación terminal del Supervisor enviada');

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Incompleto');

        $this->actingAs($student)
            ->get(route('alumno.practice-tracking.index'))
            ->assertOk()
            ->assertSee(route('alumno.practice-records.show', $request));

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertOk()
            ->assertSee(route('coordinacion.practice-records.show', $request));
    }

    public function test_only_the_owner_student_and_coordinator_can_open_a_record(): void
    {
        [$student, $tutor, $supervisor, $coordinator, $request] = $this->createPractice();
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);

        $this->actingAs($otherStudent)
            ->get(route('alumno.practice-records.show', $request))
            ->assertNotFound();

        $this->actingAs($tutor)
            ->get(route('alumno.practice-records.show', $request))
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
        $acceptance = $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "practice-acceptances/{$request->id}/acceptance.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => 'accepted',
            'submitted_at' => now(),
        ]);

        $letterPath = "practice-termination-letters/{$request->id}/cierre.pdf";
        Storage::disk('local')->put($letterPath, 'carta');
        $letter = $request->terminationLetter()->create([
            'file_path' => $letterPath,
            'original_name' => 'cierre.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 5,
            'status' => PracticeTerminationLetterStatus::Accepted,
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
            ->assertSee('Objetivo documentado')
            ->assertSee('cierre.pdf')
            ->assertSee('evidencia.pdf')
            ->assertSee(route('alumno.practice-evaluations.show', $tutorEvaluation))
            ->assertSee(route('alumno.practice-supervisor-evaluations.show', $supervisorEvaluation));

        $this->get(route('alumno.practice-termination-letters.file', $letter))->assertOk();
        $this->get(route('practice-reports.attachments.download', $attachment))->assertOk();

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-records.show', $request))
            ->assertOk()
            ->assertSee('Completo')
            ->assertSee(route('coordinacion.practice-evaluations.show', $tutorEvaluation))
            ->assertSee(route('coordinacion.practice-supervisor-evaluations.show', $supervisorEvaluation));
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
