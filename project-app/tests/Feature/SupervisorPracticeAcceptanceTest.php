<?php

namespace Tests\Feature;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Supervisor\PracticeAcceptances\Upload;
use App\Models\Company;
use App\Models\PracticeAcceptanceSubmission;
use App\Models\PracticeLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SupervisorPracticeAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_sees_only_issued_letters_for_their_company_not_application_details(): void
    {
        [$supervisor, $letter] = $this->createApprovedLetter();
        [, $otherLetter] = $this->createApprovedLetter();

        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-letters.index'))
            ->assertOk()
            ->assertSee($letter->folio)
            ->assertDontSee($otherLetter->folio)
            ->assertDontSee('Correo UCOL')
            ->assertDontSee('Solicitud de práctica');

        $this->get(route('supervisor.practice-letters.show', $letter))
            ->assertOk()
            ->assertSee('00000000000')
            ->assertSee('0000-0000')
            ->assertSee('Cargar carta de aceptación');

        $this->get(route('supervisor.practice-letters.show', $otherLetter))
            ->assertNotFound();
    }

    public function test_supervisor_can_upload_supported_file_to_private_storage(): void
    {
        Storage::fake('local');
        [$supervisor, $letter] = $this->createApprovedLetter();
        $this->actingAs($supervisor);

        Livewire::test(Upload::class, ['practiceLetter' => $letter])
            ->set('acceptanceFile', UploadedFile::fake()->createWithContent('aceptacion.pdf', "%PDF-1.4\nCarta de aceptación"))
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('supervisor.practice-letters.index'));

        $submission = PracticeAcceptanceSubmission::query()->sole();
        $this->assertSame(PracticeAcceptanceStatus::PendingReview, $submission->status);
        $this->assertSame(1, $submission->version);
        $this->assertSame($supervisor->id, $submission->supervisor_id);
        $this->assertSame('application/pdf', $submission->mime_type);
        Storage::disk('local')->assertExists($submission->file_path);
    }

    public function test_invalid_type_and_files_over_twenty_megabytes_are_rejected(): void
    {
        Storage::fake('local');
        [$supervisor, $letter] = $this->createApprovedLetter();
        $this->actingAs($supervisor);

        Livewire::test(Upload::class, ['practiceLetter' => $letter])
            ->set('acceptanceFile', UploadedFile::fake()->create('carta.exe', 100, 'application/x-msdownload'))
            ->call('submit')
            ->assertHasErrors('acceptanceFile');

        Livewire::test(Upload::class, ['practiceLetter' => $letter])
            ->set('acceptanceFile', UploadedFile::fake()->createWithContent(
                'carta.pdf',
                "%PDF-1.4\n".str_repeat('0', 20 * 1024 * 1024),
            ))
            ->call('submit')
            ->assertHasErrors('acceptanceFile');

        $this->assertDatabaseCount('practice_acceptance_submissions', 0);
        $this->assertSame([], Storage::disk('local')->allFiles('practice-acceptances'));
    }

    public function test_returned_upload_can_be_resubmitted_without_losing_previous_version(): void
    {
        Storage::fake('local');
        [$supervisor, $letter] = $this->createApprovedLetter();
        $oldPath = "practice-acceptances/{$letter->practiceRequest->id}/previous.pdf";
        Storage::disk('local')->put($oldPath, 'previous');
        $first = $letter->practiceRequest->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => $oldPath,
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 8,
            'status' => PracticeAcceptanceStatus::Returned,
            'review_comment' => 'Falta la firma.',
            'reviewed_by' => User::factory()->create(['role' => UserRole::Coordinator])->id,
            'submitted_at' => now()->subDay(),
            'reviewed_at' => now(),
        ]);

        $this->actingAs($supervisor);
        Livewire::test(Upload::class, ['practiceLetter' => $letter])
            ->set('acceptanceFile', UploadedFile::fake()->createWithContent(
                'aceptacion.png',
                base64_decode(
                    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jOx8AAAAASUVORK5CYII=',
                    true,
                ),
            ))
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertSame(2, PracticeAcceptanceSubmission::query()->count());
        $this->assertSame(PracticeAcceptanceStatus::Returned, $first->fresh()->status);
        $second = PracticeAcceptanceSubmission::query()->where('version', 2)->firstOrFail();
        $this->assertSame(PracticeAcceptanceStatus::PendingReview, $second->status);
        Storage::disk('local')->assertExists($oldPath);
        Storage::disk('local')->assertExists($second->file_path);
    }

    public function test_supervisor_cannot_submit_another_file_while_review_is_pending_or_after_acceptance(): void
    {
        Storage::fake('local');
        [$supervisor, $letter] = $this->createApprovedLetter();
        $this->addSubmission($supervisor, $letter, PracticeAcceptanceStatus::PendingReview);
        $this->actingAs($supervisor);

        Livewire::test(Upload::class, ['practiceLetter' => $letter])
            ->set('acceptanceFile', UploadedFile::fake()->createWithContent('aceptacion.pdf', "%PDF-1.4\nCarta de aceptación"))
            ->call('submit')
            ->assertHasErrors('acceptanceFile');

        $latest = $letter->practiceRequest->latestAcceptanceSubmission()->firstOrFail();
        $latest->update(['status' => PracticeAcceptanceStatus::Accepted]);

        $this->get(route('supervisor.practice-letters.show', $letter))
            ->assertOk()
            ->assertSee('Carta de presentación')
            ->assertDontSee(route('supervisor.practice-acceptances.upload', $letter));
        $this->get(route('supervisor.practice-acceptances.upload', $letter))
            ->assertNotFound();
        $this->assertDatabaseCount('practice_acceptance_submissions', 1);
    }

    private function createApprovedLetter(): array
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
            'folio_number' => PracticeLetter::query()->count() + 1,
            'folio' => sprintf('CP-2026-%04d', PracticeLetter::query()->count() + 1),
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'practice_location' => 'Colima',
            'start_date' => '2026-11-01',
            'end_date' => '2027-01-31',
            'issued_at' => now(),
        ]);

        return [$supervisor, $letter];
    }

    private function addSubmission(
        User $supervisor,
        PracticeLetter $letter,
        PracticeAcceptanceStatus $status,
    ): PracticeAcceptanceSubmission {
        return $letter->practiceRequest->acceptanceSubmissions()->create([
            'supervisor_id' => $supervisor->id,
            'version' => 1,
            'file_path' => "practice-acceptances/{$letter->practice_request_id}/v1.pdf",
            'file_extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 8,
            'status' => $status,
            'submitted_at' => now(),
        ]);
    }
}
