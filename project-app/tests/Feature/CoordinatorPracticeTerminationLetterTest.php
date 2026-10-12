<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\PracticeTerminationLetterStatus;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticeTerminationLetters\Index;
use App\Livewire\Coordinator\PracticeTerminationLetters\Review;
use App\Models\Company;
use App\Models\PracticeTerminationLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CoordinatorPracticeTerminationLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_can_return_letter_with_required_comment_and_student_can_see_it(): void
    {
        Storage::fake('local');
        [$coordinator, $student, $letter] = $this->createPendingLetter();
        Storage::disk('local')->put($letter->file_path, 'letter');
        $this->actingAs($coordinator);

        Livewire::test(Review::class, ['practiceTerminationLetter' => $letter])
            ->call('returnForChanges')
            ->assertHasErrors(['reviewComment' => 'required']);

        Livewire::test(Review::class, ['practiceTerminationLetter' => $letter])
            ->set('reviewComment', 'Falta la firma del responsable.')
            ->call('returnForChanges')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.practice-termination-letters.index'));

        $letter->refresh();
        $this->assertSame(PracticeTerminationLetterStatus::Returned, $letter->status);
        $this->assertSame('Falta la firma del responsable.', $letter->review_comment);
        $this->assertSame($coordinator->id, $letter->reviewed_by);
        Storage::disk('local')->assertExists($letter->file_path);

        $this->actingAs($student)
            ->get(route('alumno.practice-termination-letters.upload', $letter->practiceRequest))
            ->assertOk()
            ->assertSee('Falta la firma del responsable.');
    }

    public function test_acceptance_requires_coordinator_to_confirm_review_of_letter_contents(): void
    {
        Storage::fake('local');
        [$coordinator, , $letter] = $this->createPendingLetter();
        Storage::disk('local')->put($letter->file_path, 'letter');
        $this->actingAs($coordinator);

        Livewire::test(Review::class, ['practiceTerminationLetter' => $letter])
            ->call('approve')
            ->assertHasErrors(['reviewChecklistConfirmed' => 'accepted']);

        Livewire::test(Review::class, ['practiceTerminationLetter' => $letter])
            ->set('reviewChecklistConfirmed', true)
            ->call('approve')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.practice-termination-letters.index'));

        $this->assertSame(PracticeTerminationLetterStatus::Accepted, $letter->fresh()->status);
        $this->assertSame($coordinator->id, $letter->fresh()->reviewed_by);
        Storage::disk('local')->assertExists($letter->file_path);
        $this->get(route('coordinacion.practice-termination-letters.review', $letter))
            ->assertOk()
            ->assertSee('solo lectura');
    }

    public function test_only_coordinator_and_student_owner_can_download_the_private_letter(): void
    {
        Storage::fake('local');
        [$coordinator, $student, $letter] = $this->createPendingLetter();
        Storage::disk('local')->put($letter->file_path, 'private letter');
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $supervisor = User::factory()->create([
            'role' => UserRole::Supervisor,
            'company_id' => $letter->practiceRequest->company_id,
        ]);
        $tutor = User::factory()->create(['role' => UserRole::Tutor]);

        $this->actingAs($otherStudent)
            ->get(route('alumno.practice-termination-letters.file', $letter))
            ->assertForbidden();
        $this->actingAs($supervisor)
            ->get(route('supervisor.practice-termination-letters.file', $letter))
            ->assertForbidden();
        $this->actingAs($tutor)
            ->get(route('alumno.practice-termination-letters.file', $letter))
            ->assertForbidden();
        $this->actingAs($student)
            ->get(route('alumno.practice-termination-letters.file', $letter))
            ->assertOk()
            ->assertHeader('x-content-type-options', 'nosniff');
        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-termination-letters.file', $letter))
            ->assertOk();
    }

    public function test_coordinator_can_list_letters_but_other_roles_cannot(): void
    {
        [$coordinator, , $letter] = $this->createPendingLetter();

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-termination-letters.index'))
            ->assertOk()
            ->assertSee('Cartas de término')
            ->assertSee($letter->practiceRequest->student->name);

        $this->actingAs(User::factory()->create(['role' => UserRole::Tutor]))
            ->get(route('coordinacion.practice-termination-letters.index'))
            ->assertForbidden();
    }

    private function createPendingLetter(): array
    {
        $company = Company::query()->create(['name' => 'Empresa de práctica']);
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
        $letter = $request->terminationLetter()->create([
            'file_path' => "practice-termination-letters/{$request->id}/letter.pdf",
            'original_name' => 'letter.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 12,
            'status' => PracticeTerminationLetterStatus::PendingReview,
            'submitted_at' => now(),
        ]);

        return [$coordinator, $student, $letter];
    }
}
