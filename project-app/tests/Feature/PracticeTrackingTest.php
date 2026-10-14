<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\PracticeTracking\Index;
use App\Models\Company;
use App\Models\PracticeLetter;
use App\Models\PracticeRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class PracticeTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_can_search_filter_by_stage_and_open_practice_actions(): void
    {
        [$coordinator, , $request] = $this->createPractice('Hotel Faro', 'Ana Alumna');
        $this->actingAs($coordinator);
        $this->get(route('coordinacion.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Seguimiento de prácticas')
            ->assertSee('Solicitud')
            ->assertSee('Evaluación de Supervisor')
            ->assertSee('Evaluación de Tutor')
            ->assertSee('Ana Alumna')
            ->assertSee('Hotel Faro')
            ->assertSee('Solicitud · Pendiente')
            ->assertSee('Revisar solicitud')
            ->assertDontSee('Abrir práctica')
            ->assertSee('Abrir expediente')
            ->assertSee('class="accordion-collapse collapse"', false)
            ->assertSee('aria-expanded="false"', false);

        Livewire::test(Index::class)
            ->set('search', 'Hotel Faro')
            ->assertSee('Ana Alumna')
            ->set('search', 'Empresa inexistente')
            ->assertSee('No hay prácticas que coincidan');

        Livewire::test(Index::class)
            ->set('stageFilter', 'Solicitud')
            ->set('stateFilter', 'Pendiente')
            ->assertSee('Coordinación debe revisar la solicitud');

        $this->assertSame(PracticeRequestStatus::Pending, $request->fresh()->status);
    }

    public function test_returned_request_action_names_the_correction_destination(): void
    {
        [, $student, $request] = $this->createPractice('Hotel Faro', 'Ana Alumna');
        $request->update(['status' => PracticeRequestStatus::Returned]);

        $this->actingAs($student)
            ->get(route('alumno.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Corregir solicitud')
            ->assertSee('href="'.route('alumno.practice-request.show').'"', false)
            ->assertDontSee('Abrir práctica');
    }

    public function test_coordinator_tracking_loads_the_latest_practice_report_version(): void
    {
        [$coordinator, , $request] = $this->createPractice('Hotel Faro', 'Ana Alumna');
        $report = $request->practiceReport()->create([]);
        $versionData = [
            'status' => PracticeReportStatus::Draft,
            'company_name' => $request->company->name,
            'area' => 'Área de prueba',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivos ficticios',
            'activities' => 'Actividades ficticias',
            'outcomes' => 'Resultados ficticios',
            'conclusions' => 'Conclusiones ficticias',
            'recommendations' => 'Recomendaciones ficticias',
        ];
        $report->versions()->create(['version' => 1, ...$versionData]);
        $latestVersion = $report->versions()->create(['version' => 2, ...$versionData]);

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Seguimiento de prácticas')
            ->assertSee('Ana Alumna');

        $this->assertSame($latestVersion->id, $report->fresh()->latestVersion->id);
    }

    public function test_tracking_orders_a_request_by_its_latest_related_version_update(): void
    {
        [$coordinator, , $olderParent] = $this->createPractice('Hotel Uno', 'Alumna Versión Reciente');
        [, , $newerParent] = $this->createPractice('Hotel Dos', 'Alumno Padre Reciente');
        $report = $olderParent->practiceReport()->create([]);
        $version = $report->versions()->create([
            'version' => 1,
            'status' => PracticeReportStatus::Draft,
            'company_name' => 'Hotel Uno',
            'area' => 'Área de prueba',
            'period' => 'Periodo de prueba',
            'objectives' => 'Objetivos ficticios',
            'activities' => 'Actividades ficticias',
            'outcomes' => 'Resultados ficticios',
            'conclusions' => 'Conclusiones ficticias',
            'recommendations' => 'Recomendaciones ficticias',
        ]);

        DB::table('practice_requests')->where('id', $olderParent->id)
            ->update(['updated_at' => '2026-01-01 12:00:00']);
        DB::table('practice_requests')->where('id', $newerParent->id)
            ->update(['updated_at' => '2026-01-03 12:00:00']);
        DB::table('practice_report_versions')->where('id', $version->id)
            ->update(['updated_at' => '2026-01-04 12:00:00']);

        $this->actingAs($coordinator);

        Livewire::test(Index::class)
            ->assertViewHas('rows', fn ($rows) => collect($rows->items())
                ->pluck('request.student.name')
                ->all() === ['Alumna Versión Reciente', 'Alumno Padre Reciente']);
    }

    public function test_students_only_see_their_own_practices_and_their_pending_actions(): void
    {
        [$coordinator, $student, $request] = $this->createPractice('Hotel Propio', 'Alumno Propio');
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $request->company_id,
        ]);
        $request->update(['status' => PracticeRequestStatus::Approved]);
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
        $otherRequest = $this->createPractice('Hotel Ajeno', 'Alumno Ajeno')[2];

        $this->actingAs($student)
            ->get(route('alumno.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Hotel Propio')
            ->assertSee('Alumno Propio')
            ->assertDontSee('Hotel Ajeno')
            ->assertDontSee('Alumno Ajeno')
            ->assertSee('El alumno debe registrar el plan de trabajo')
            ->assertSee('Consultar solicitud')
            ->assertSee('Tienes 1 acción pendiente');

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertOk()
            ->assertSee($request->student->name)
            ->assertSee($otherRequest->student->name);
    }

    public function test_roles_without_a_practice_scope_are_forbidden(): void
    {
        User::factory()->create(['role' => UserRole::Tutor]);
        $this->actingAs(User::factory()->create(['role' => UserRole::Coordinator]))
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertOk();

        $this->actingAs(User::factory()->create(['role' => UserRole::Student]))
            ->get(route('coordinacion.practice-tracking.index'))
            ->assertForbidden();
    }

    public function test_company_supervisor_sees_a_pending_acceptance_action_only_for_their_company(): void
    {
        [$coordinator, , $request] = $this->createPractice('Hotel Faro', 'Ana Alumna');
        $request->update(['status' => PracticeRequestStatus::Approved]);
        $letter = $this->createLetter($request, $coordinator);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $request->company_id,
        ]);
        [$otherCoordinator, , $otherRequest] = $this->createPractice('Hotel Ajeno', 'Alumno Ajeno');
        $otherRequest->update(['status' => PracticeRequestStatus::Approved]);
        $otherLetter = $this->createLetter($otherRequest, $otherCoordinator);

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-tracking.index'))
            ->assertOk()
            ->assertSee('Hotel Faro')
            ->assertDontSee('Hotel Ajeno')
            ->assertSee('El supervisor debe cargar la carta de aceptación')
            ->assertSee('Consultar carta de presentación')
            ->assertSee('Cargar carta de aceptación')
            ->assertSee('href="'.route('supervisor.practice-letters.show', $letter).'"', false)
            ->assertSee('href="'.route('supervisor.practice-acceptances.upload', $letter).'"', false)
            ->assertDontSee('href="'.route('supervisor.practice-letters.show', $otherLetter).'"', false)
            ->assertDontSee('href="'.route('supervisor.practice-acceptances.upload', $otherLetter).'"', false)
            ->assertSee('acción pendiente');
    }

    private function createLetter(PracticeRequest $request, User $coordinator): PracticeLetter
    {
        $folioNumber = PracticeLetter::query()->count() + 1;

        return $request->letter()->create([
            'coordinator_id' => $coordinator->id,
            'folio_year' => 2026,
            'folio_number' => $folioNumber,
            'folio' => sprintf('CP-2026-%04d', $folioNumber),
            'letter_recipient' => $request->letter_recipient,
            'position' => $request->position,
            'practice_location' => $request->practice_location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'issued_at' => now(),
        ]);
    }

    private function createPractice(string $companyName, string $studentName): array
    {
        $company = Company::query()->create(['name' => $companyName]);
        $student = User::factory()->create([
            'name' => $studentName,
            'role' => UserRole::Student,
        ]);
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
            'status' => PracticeRequestStatus::Pending,
            'non_responsiva_accepted_at' => now(),
        ]);

        return [$coordinator, $student, $request];
    }
}
