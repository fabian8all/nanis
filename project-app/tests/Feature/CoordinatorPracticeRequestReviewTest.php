<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Coordinator\PracticeRequests\Review;
use App\Livewire\Student\PracticeRequests\Show as StudentPracticeRequest;
use App\Models\Company;
use App\Models\PracticeLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CoordinatorPracticeRequestReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_coordinators_can_view_requests_or_print_letters(): void
    {
        [$student, , $request] = $this->createPendingRequest();

        $this->actingAs($student)
            ->get(route('coordinacion.practice-requests.index'))
            ->assertForbidden();

        $this->actingAs($student)
            ->get(route('coordinacion.practice-requests.review', $request))
            ->assertForbidden();
    }

    public function test_review_queue_and_detail_do_not_expose_nss_or_account_number(): void
    {
        [$student, , $request] = $this->createPendingRequest();
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);

        $this->actingAs($coordinator)
            ->get(route('coordinacion.practice-requests.index'))
            ->assertOk()
            ->assertSee($student->name)
            ->assertDontSee('00000000000')
            ->assertDontSee('00000000');

        $this->get(route('coordinacion.practice-requests.review', $request))
            ->assertOk()
            ->assertSee('Datos protegidos')
            ->assertDontSee('00000000000')
            ->assertDontSee('00000000');
    }

    public function test_return_requires_comment_and_records_decision_for_student(): void
    {
        [$student, , $request] = $this->createPendingRequest();
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator);

        Livewire::test(Review::class, ['practiceRequest' => $request])
            ->call('returnForChanges')
            ->assertHasErrors(['reviewComment' => 'required']);

        Livewire::test(Review::class, ['practiceRequest' => $request])
            ->set('reviewComment', 'Aclara el lugar de práctica.')
            ->call('returnForChanges')
            ->assertHasNoErrors()
            ->assertRedirect(route('coordinacion.practice-requests.index'));

        $this->assertSame(PracticeRequestStatus::Returned, $request->fresh()->status);
        $this->assertDatabaseHas('practice_request_reviews', [
            'practice_request_id' => $request->id,
            'coordinator_id' => $coordinator->id,
            'decision' => PracticeRequestStatus::Returned->value,
            'comment' => 'Aclara el lugar de práctica.',
        ]);

        $this->actingAs($student)
            ->get(route('alumno.practice-request.show'))
            ->assertOk()
            ->assertSee('Aclara el lugar de práctica.');
    }

    public function test_revisions_remain_in_history_after_student_resubmits_and_is_returned_again(): void
    {
        [$student, , $request] = $this->createPendingRequest();
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);

        $this->actingAs($coordinator);
        Livewire::test(Review::class, ['practiceRequest' => $request])
            ->set('reviewComment', 'Corrige el lugar.')
            ->call('returnForChanges');

        $this->actingAs($student);
        Livewire::test(StudentPracticeRequest::class)
            ->set('practiceLocation', 'Edificio principal')
            ->call('submit')
            ->assertHasNoErrors();

        $this->actingAs($coordinator);
        Livewire::test(Review::class, ['practiceRequest' => $request->fresh()])
            ->set('reviewComment', 'Confirma el turno.')
            ->call('returnForChanges')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('practice_request_reviews', 2);
        $this->assertDatabaseHas('practice_request_reviews', [
            'practice_request_id' => $request->id,
            'comment' => 'Corrige el lugar.',
        ]);
        $this->assertDatabaseHas('practice_request_reviews', [
            'practice_request_id' => $request->id,
            'comment' => 'Confirma el turno.',
        ]);
        $this->assertSame(PracticeRequestStatus::Returned, $request->fresh()->status);
    }

    public function test_approval_creates_yearly_letter_folio_without_changing_original_request(): void
    {
        [$student, , $request] = $this->createPendingRequest();
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator);

        Livewire::test(Review::class, ['practiceRequest' => $request])
            ->set('letterRecipient', 'Gerencia')
            ->set('position', 'Auxiliar de operaciones')
            ->set('practiceLocation', 'Sucursal Centro')
            ->set('startDate', '2026-11-10')
            ->set('endDate', '2027-02-15')
            ->set('reviewComment', 'Aprobada.')
            ->call('approve')
            ->assertHasNoErrors();

        $letter = PracticeLetter::query()->sole();
        $this->assertSame('CP-2026-0001', $letter->folio);
        $originalRequest = $request->fresh();
        $this->assertSame(PracticeRequestStatus::Approved, $originalRequest->status);
        $this->assertDatabaseHas('practice_requests', [
            'id' => $request->id,
            'letter_recipient' => 'Dirección original',
            'position' => 'Puesto solicitado',
            'practice_location' => 'Lugar solicitado',
        ]);
        $this->assertSame('2026-10-01', $originalRequest->start_date->toDateString());
        $this->assertSame('2026-12-01', $originalRequest->end_date->toDateString());
        $this->assertSame('Gerencia', $letter->letter_recipient);
        $this->assertSame('Sucursal Centro', $letter->practice_location);

        $this->get(route('coordinacion.practice-letters.print', $letter))
            ->assertOk()
            ->assertSee('CP-2026-0001')
            ->assertSee('0000-0000')
            ->assertSee('00000000000')
            ->assertSee('Imprimir para firma')
            ->assertSee('240 horas')
            ->assertDontSee('800 horas');
    }

    public function test_consecutive_approved_letters_receive_distinct_folios(): void
    {
        [, , $firstRequest] = $this->createPendingRequest();
        [, , $secondRequest] = $this->createPendingRequest();
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);
        $this->actingAs($coordinator);

        foreach ([$firstRequest, $secondRequest] as $request) {
            Livewire::test(Review::class, ['practiceRequest' => $request])
                ->call('approve')
                ->assertHasNoErrors();
        }

        $this->assertSame(
            ['CP-2026-0001', 'CP-2026-0002'],
            PracticeLetter::query()->orderBy('folio_number')->pluck('folio')->all(),
        );
    }

    private function createPendingRequest(): array
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $company = Company::query()->create(['name' => 'Hotel de Prueba']);
        $request = $student->practiceRequests()->create([
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-10-01',
            'practice_location' => 'Lugar solicitado',
            'letter_recipient' => 'Dirección original',
            'position' => 'Puesto solicitado',
            'end_date' => '2026-12-01',
            'status' => PracticeRequestStatus::Pending,
            'non_responsiva_accepted_at' => now(),
        ]);

        return [$student, $company, $request];
    }
}
