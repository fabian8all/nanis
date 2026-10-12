<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\PracticeTerminationLetterStatus;
use App\Enums\UserRole;
use App\Livewire\Student\PracticeTerminationLetters\Upload;
use App\Models\Company;
use App\Models\PracticeTerminationLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StudentPracticeTerminationLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_upload_a_supported_letter_to_private_storage(): void
    {
        Storage::fake('local');
        [$student, $request] = $this->createApprovedPractice();
        $this->actingAs($student);

        $this->get(route('alumno.practice-termination-letters.upload', $request))
            ->assertOk()
            ->assertSee('240 horas')
            ->assertSee('firma del responsable empresarial');

        Livewire::test(Upload::class, ['practiceRequest' => $request])
            ->set('letterFile', UploadedFile::fake()->createWithContent(
                'carta-termino.pdf',
                "%PDF-1.4\nCarta que acredita las 240 horas",
            ))
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('alumno.practice-termination-letters.upload', $request));

        $letter = PracticeTerminationLetter::query()->sole();
        $this->assertSame(PracticeTerminationLetterStatus::PendingReview, $letter->status);
        $this->assertSame('carta-termino.pdf', $letter->original_name);
        $this->assertSame('application/pdf', $letter->mime_type);
        Storage::disk('local')->assertExists($letter->file_path);
    }

    public function test_student_can_replace_a_returned_letter_and_only_current_file_is_retained(): void
    {
        Storage::fake('local');
        [$student, $request] = $this->createApprovedPractice();
        $oldPath = "practice-termination-letters/{$request->id}/old.pdf";
        Storage::disk('local')->put($oldPath, 'old letter');
        $letter = $request->terminationLetter()->create([
            'file_path' => $oldPath,
            'original_name' => 'old.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 10,
            'status' => PracticeTerminationLetterStatus::Returned,
            'review_comment' => 'Falta la firma del responsable.',
            'submitted_at' => now()->subDay(),
            'reviewed_at' => now(),
        ]);

        $this->actingAs($student);
        Livewire::test(Upload::class, ['practiceRequest' => $request])
            ->assertSee('Falta la firma del responsable.')
            ->set('letterFile', UploadedFile::fake()->createWithContent(
                'carta-corregida.png',
                base64_decode(
                    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jOx8AAAAASUVORK5CYII=',
                    true,
                ),
            ))
            ->call('submit')
            ->assertHasNoErrors();

        $letter->refresh();
        $this->assertSame(PracticeTerminationLetterStatus::PendingReview, $letter->status);
        $this->assertNull($letter->review_comment);
        $this->assertSame(1, PracticeTerminationLetter::query()->count());
        $this->assertNotSame($oldPath, $letter->file_path);
        Storage::disk('local')->assertMissing($oldPath);
        Storage::disk('local')->assertExists($letter->file_path);
    }

    public function test_invalid_files_and_files_over_twenty_megabytes_are_rejected(): void
    {
        Storage::fake('local');
        [$student, $request] = $this->createApprovedPractice();
        $this->actingAs($student);

        Livewire::test(Upload::class, ['practiceRequest' => $request])
            ->set('letterFile', UploadedFile::fake()->create('carta.exe', 100, 'application/x-msdownload'))
            ->call('submit')
            ->assertHasErrors('letterFile');

        Livewire::test(Upload::class, ['practiceRequest' => $request])
            ->set('letterFile', UploadedFile::fake()->createWithContent(
                'carta.pdf',
                "%PDF-1.4\n".str_repeat('0', 20 * 1024 * 1024),
            ))
            ->call('submit')
            ->assertHasErrors('letterFile');

        $this->assertDatabaseCount('practice_termination_letters', 0);
        $this->assertSame([], Storage::disk('local')->allFiles('practice-termination-letters'));
    }

    public function test_student_cannot_upload_when_the_letter_is_pending_or_accepted(): void
    {
        Storage::fake('local');
        [$student, $request] = $this->createApprovedPractice();
        $path = "practice-termination-letters/{$request->id}/letter.pdf";
        Storage::disk('local')->put($path, 'letter');
        $letter = $request->terminationLetter()->create([
            'file_path' => $path,
            'original_name' => 'letter.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 6,
            'status' => PracticeTerminationLetterStatus::PendingReview,
            'submitted_at' => now(),
        ]);

        $this->actingAs($student);
        Livewire::test(Upload::class, ['practiceRequest' => $request])
            ->set('letterFile', UploadedFile::fake()->createWithContent('new.pdf', "%PDF-1.4\nnew"))
            ->call('submit')
            ->assertHasErrors('letterFile');

        $letter->update(['status' => PracticeTerminationLetterStatus::Accepted]);
        Livewire::test(Upload::class, ['practiceRequest' => $request])
            ->set('letterFile', UploadedFile::fake()->createWithContent('new.pdf', "%PDF-1.4\nnew"))
            ->call('submit')
            ->assertHasErrors('letterFile');

        $this->assertSame($path, $letter->fresh()->file_path);
        Storage::disk('local')->assertExists($path);
    }

    public function test_only_the_student_owner_can_open_the_upload_page(): void
    {
        [, $request] = $this->createApprovedPractice();
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $tutor = User::factory()->create(['role' => UserRole::Tutor]);

        $this->actingAs($otherStudent)
            ->get(route('alumno.practice-termination-letters.upload', $request))
            ->assertNotFound();
        $this->actingAs($tutor)
            ->get(route('alumno.practice-termination-letters.upload', $request))
            ->assertForbidden();
    }

    private function createApprovedPractice(): array
    {
        $company = Company::query()->create(['name' => 'Empresa de práctica']);
        $student = User::factory()->create(['role' => UserRole::Student]);
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

        return [$student, $request];
    }
}
