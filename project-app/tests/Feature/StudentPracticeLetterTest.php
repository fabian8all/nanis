<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPracticeLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_and_print_their_approved_letter(): void
    {
        [$student, $letter] = $this->createLetter();

        $this->actingAs($student)
            ->get(route('alumno.practice-letters.show', $letter))
            ->assertOk()
            ->assertSee('CP-2026-0001')
            ->assertSee('Hotel de Prueba')
            ->assertSee('00000000000')
            ->assertSee('0000-0000')
            ->assertSee('Imprimir / guardar como PDF');
    }

    public function test_letter_access_requires_authentication_student_role_and_ownership(): void
    {
        [$student, $letter] = $this->createLetter();
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);

        $this->get(route('alumno.practice-letters.show', $letter))
            ->assertRedirect(route('login'));

        $this->actingAs($otherStudent)
            ->get(route('alumno.practice-letters.show', $letter))
            ->assertNotFound();

        $this->actingAs($coordinator)
            ->get(route('alumno.practice-letters.show', $letter))
            ->assertForbidden();

        $this->assertSame($student->id, $letter->practiceRequest->user_id);
    }

    public function test_approved_request_links_to_its_letter_but_pending_request_does_not(): void
    {
        [$student, $letter] = $this->createLetter();

        $this->actingAs($student)
            ->get(route('alumno.practice-request.show'))
            ->assertOk()
            ->assertSee('Tu solicitud fue aprobada y la carta de presentación está disponible.')
            ->assertSee(route('alumno.practice-letters.show', $letter));

        [$pendingStudent, $pendingLetter] = $this->createLetter(PracticeRequestStatus::Pending);

        $this->actingAs($pendingStudent)
            ->get(route('alumno.practice-letters.show', $pendingLetter))
            ->assertNotFound();

        $this->get(route('alumno.practice-request.show'))
            ->assertOk()
            ->assertDontSee(route('alumno.practice-letters.show', $pendingLetter));
    }

    private function createLetter(
        PracticeRequestStatus $status = PracticeRequestStatus::Approved,
    ): array {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $company = Company::query()->create(['name' => 'Hotel de Prueba']);
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
            'status' => $status,
            'non_responsiva_accepted_at' => now(),
        ]);
        $number = PracticeLetter::query()->count() + 1;
        $letter = $request->letter()->create([
            'coordinator_id' => $coordinator->id,
            'folio_year' => 2026,
            'folio_number' => $number,
            'folio' => sprintf('CP-2026-%04d', $number),
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'practice_location' => 'Colima',
            'start_date' => '2026-11-01',
            'end_date' => '2027-01-31',
            'issued_at' => now(),
        ]);

        return [$student, $letter];
    }
}
