<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticeAcceptances\Review;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CoordinatorPracticeAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_can_review_and_return_upload_with_required_comment(): void
    {
        [$coordinator, $supervisor, $letter, $submission] = $this->createPendingSubmission();
        Storage::fake('local');
        Storage::disk('local')->put($submission->file_path, 'signed acceptance');
        $this->actingAs($coordinator);

        Livewire::test(Review::class, ['practiceAcceptance' => $submission])
            ->call('returnForChanges')
            ->assertHasErrors(['reviewComment' => 'required']);

        Livewire::test(Review::class, ['practiceAcceptance' => $submission])
            ->set('reviewComment', 'Falta la firma de la empresa.')
            ->call('returnForChanges')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.practice-acceptances.index'));

        $this->assertSame(PracticeAcceptanceStatus::Returned, $submission->fresh()->status);
        $this->assertSame('Falta la firma de la empresa.', $submission->fresh()->review_comment);
        $this->assertSame($coordinator->id, $submission->fresh()->reviewed_by);
        Storage::disk('local')->assertExists($submission->file_path);

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-letters.index'))
            ->assertOk()
            ->assertSee($letter->folio)
            ->assertSee('Falta la firma de la empresa.');
    }

    public function test_approval_closes_company_upload_and_preserves_reviewed_version(): void
    {
        [$coordinator, $supervisor, $letter, $submission] = $this->createPendingSubmission();
        Storage::fake('local');
        Storage::disk('local')->put($submission->file_path, 'signed acceptance');
        $this->actingAs($coordinator);

        Livewire::test(Review::class, ['practiceAcceptance' => $submission])
            ->set('reviewComment', 'Documento legible.')
            ->call('approve')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.practice-acceptances.index'));

        $this->assertSame(PracticeAcceptanceStatus::Accepted, $submission->fresh()->status);
        $this->assertSame('Documento legible.', $submission->fresh()->review_comment);
        Storage::disk('local')->assertExists($submission->file_path);

        $this->get(route('coordinacion.practice-acceptances.index'))
            ->assertOk()
            ->assertSee('Historial de cartas revisadas')
            ->assertSee($letter->folio)
            ->assertSee('Documento legible.');
        $this->get(route('coordinacion.practice-acceptances.review', $submission))
            ->assertOk()
            ->assertSee('Historial de carta de aceptación')
            ->assertSee('solo lectura')
            ->assertSee('Documento legible.');

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-letters.index'))
            ->assertOk()
            ->assertDontSee($letter->folio);

        $this->get(route('supervisor.practice-acceptances.upload', $letter))
            ->assertNotFound();
        $this->assertDatabaseCount('practice_acceptance_submissions', 1);
    }

    public function test_only_coordinators_and_the_assigned_company_supervisor_can_download_private_file(): void
    {
        [$coordinator, $supervisor, , $submission] = $this->createPendingSubmission();
        Storage::fake('local');
        Storage::disk('local')->put($submission->file_path, 'private acceptance content');
        $otherCompany = Company::query()->create(['name' => 'Hotel Otro']);
        $otherSupervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $otherCompany->id,
        ]);

        $this->get(route('coordinacion.practice-acceptances.file', $submission))
            ->assertRedirect(route('login'));
        $this->actingAs($otherSupervisor)
            ->get(route('supervisor.practice-acceptances.file', $submission))
            ->assertForbidden();
        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-acceptances.file', $submission))
            ->assertOk()
            ->assertHeader('content-disposition');
        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-acceptances.file', $submission))
            ->assertOk();

        $this->actingAs($supervisor)
            ->get(route('coordinacion.practice-acceptances.index'))
            ->assertForbidden();
    }

    private function createPendingSubmission(): array
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
        $submission = $request->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "practice-acceptances/{$request->id}/acceptance.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 8,
            'status' => PracticeAcceptanceStatus::PendingReview,
            'submitted_at' => now(),
        ]);

        return [$coordinator, $supervisor, $letter, $submission];
    }
}
