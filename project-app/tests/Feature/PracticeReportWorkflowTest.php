<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticeReports\Index as CoordinatorReports;
use App\Livewire\Student\PracticeReports\Show as StudentReport;
use App\Livewire\Tutor\PracticeReports\Index as TutorReportQueue;
use App\Livewire\Tutor\PracticeReports\Review as TutorReview;
use App\Models\Company;
use App\Models\PracticeReportVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PracticeReportWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_assigns_tutor_student_submits_and_tutor_approves_with_private_annex(): void
    {
        Storage::fake('local');
        [$student, $tutor, $coordinator, $request] = $this->createEligiblePractice();
        $this->actingAs($student);

        Livewire::test(StudentReport::class, ['practiceRequest' => $request])
            ->set('area', 'Operaciones')
            ->set('period', 'Enero a marzo')
            ->set('objectives', 'Mejorar procesos')
            ->set('activities', 'Documentar actividades')
            ->set('outcomes', 'Procesos documentados')
            ->set('conclusions', 'La mejora fue útil')
            ->set('recommendations', 'Continuar seguimiento')
            ->call('submit')
            ->assertHasErrors(['report']);

        $this->actingAs($coordinator);
        Livewire::test(CoordinatorReports::class)
            ->set("tutorAssignments.{$request->id}", (string) $tutor->id)
            ->call('assignTutor', $request->id)
            ->assertHasNoErrors();
        $this->assertSame($tutor->id, $request->fresh()->tutor_id);

        $this->actingAs($student);
        Livewire::test(StudentReport::class, ['practiceRequest' => $request])
            ->set('area', 'Operaciones')
            ->set('period', 'Enero a marzo')
            ->set('objectives', 'Mejorar procesos')
            ->set('activities', 'Documentar actividades')
            ->set('outcomes', 'Procesos documentados')
            ->set('conclusions', 'La mejora fue útil')
            ->set('recommendations', 'Continuar seguimiento')
            ->call('saveDraft')
            ->assertHasNoErrors()
            ->set('attachments', [UploadedFile::fake()->createWithContent('evidencia.pdf', "%PDF-1.4\ncontenido de prueba")])
            ->call('submit')
            ->assertHasNoErrors();

        $version = PracticeReportVersion::query()->sole();
        $this->assertSame(PracticeReportStatus::PendingTutorReview, $version->status);
        $this->actingAs($tutor)
            ->get(route('tutor.practice-reports.index'))
            ->assertOk()
            ->assertSee('Pendiente de revisión por el Tutor')
            ->assertDontSee('pending_tutor_review');
        $attachment = $version->attachments()->sole();
        Storage::disk('local')->assertExists($attachment->file_path);
        $this->get(route('practice-reports.attachments.download', $attachment))->assertOk();

        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $this->actingAs($otherStudent)
            ->get(route('practice-reports.attachments.download', $attachment))
            ->assertForbidden();
        $otherTutor = User::factory()->create(['role' => UserRole::Tutor]);
        $this->actingAs($otherTutor)
            ->get(route('tutor.practice-reports.review', $version))
            ->assertNotFound();

        $this->actingAs($tutor);
        Livewire::test(TutorReview::class, ['practiceReportVersion' => $version])
            ->call('approve')
            ->assertHasNoErrors();
        $version->refresh();
        $this->assertSame(PracticeReportStatus::Approved, $version->status);
        $this->assertSame($version->id, $version->report->fresh()->approved_version_id);

        $this->actingAs($coordinator);
        Livewire::test(CoordinatorReports::class)
            ->call('reopen', $version->id)
            ->assertHasNoErrors();
        $version->refresh();
        $this->assertSame(PracticeReportStatus::Approved, $version->status);
        $this->assertDatabaseHas('practice_report_versions', [
            'practice_report_id' => $version->practice_report_id,
            'version' => 2,
            'status' => PracticeReportStatus::Draft->value,
        ]);
        $this->actingAs($student)
            ->get(route('alumno.practice-reports.show', $request))
            ->assertOk()
            ->assertSee('Versión aprobada: 1')
            ->assertSee('Versión 1 — Aprobada')
            ->assertDontSee('approved')
            ->assertDontSee('Está cerrado.');
    }

    public function test_tutor_requires_general_comment_and_returned_report_is_resubmitted_as_new_version(): void
    {
        [$student, $tutor, , $request] = $this->createEligiblePractice();
        $request->update(['tutor_id' => $tutor->id]);
        $this->actingAs($student);
        Livewire::test(StudentReport::class, ['practiceRequest' => $request])
            ->set('area', 'Recepción')
            ->set('period', 'Primer trimestre')
            ->set('objectives', 'Objetivo')
            ->set('activities', 'Actividad')
            ->set('outcomes', 'Resultado')
            ->set('conclusions', 'Conclusión')
            ->set('recommendations', 'Recomendación')
            ->call('submit')
            ->assertHasNoErrors();

        $version = PracticeReportVersion::query()->sole();
        $this->actingAs($tutor);
        Livewire::test(TutorReview::class, ['practiceReportVersion' => $version])
            ->call('returnForChanges')
            ->assertHasErrors(['reviewComment' => 'required']);
        Livewire::test(TutorReview::class, ['practiceReportVersion' => $version])
            ->set('reviewComment', 'Explica mejor los resultados.')
            ->set('sectionComments.activities', 'Incluye las fechas y responsables.')
            ->call('returnForChanges')
            ->assertHasNoErrors();

        $this->actingAs($student);
        Livewire::test(StudentReport::class, ['practiceRequest' => $request])
            ->assertSet('activities', 'Actividad')
            ->set('activities', 'Actividad corregida')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('practice_report_versions', 2);
        $this->assertSame(PracticeReportStatus::Returned, $version->fresh()->status);
        $this->assertSame('Explica mejor los resultados.', $version->fresh()->review_comment);
        $this->assertSame(PracticeReportStatus::PendingTutorReview, PracticeReportVersion::query()->latest('version')->firstOrFail()->status);
        $this->assertSame('Actividad', $version->activities);
    }

    public function test_coordinator_and_tutor_report_lists_search_by_the_visible_student(): void
    {
        [$student, $tutor, $coordinator, $request] = $this->createEligiblePractice();
        $request->update(['tutor_id' => $tutor->id]);
        $report = $request->practiceReport()->create([]);
        $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::Draft,
            'company_name' => $request->company->name,
            'area' => 'Operaciones',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivo',
            'activities' => 'Actividades',
            'outcomes' => 'Resultados',
            'conclusions' => 'Conclusiones',
            'recommendations' => 'Recomendaciones',
        ]);

        $this->actingAs($coordinator);
        Livewire::test(CoordinatorReports::class)
            ->set('search', $student->name)
            ->set('tutorSearch', $tutor->name)
            ->assertViewHas('requests', fn ($requests) => $requests->total() === 1)
            ->assertViewHas('tutors', fn ($tutors) => $tutors->total() === 1)
            ->assertSee($student->name);

        $this->actingAs($tutor);
        Livewire::test(TutorReportQueue::class)
            ->set('search', $student->name)
            ->assertViewHas('versions', fn ($versions) => $versions->total() === 1)
            ->assertSee($student->name);
    }

    private function createEligiblePractice(): array
    {
        $company = Company::query()->create(['name' => 'Empresa de práctica']);
        $student = User::factory()->create(['role' => UserRole::Student]);
        $tutor = User::factory()->create(['role' => UserRole::Tutor]);
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
        $request->acceptanceSubmissions()->create([
            'supervisor_id' => User::factory()->create([
                'role' => UserRole::Supervisor,
                'company_id' => $company->id,
            ])->id,
            'version' => 1,
            'file_path' => "acceptances/{$request->id}.pdf",
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

        return [$student, $tutor, $coordinator, $request];
    }
}
