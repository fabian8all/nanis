<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Student\PracticeRequests\Show as StudentPracticeRequest;
use App\Models\Company;
use App\Models\PracticeRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class StudentPracticeRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_request_for_selected_active_company_and_accept_non_responsiva(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $company = Company::query()->create(['name' => 'Hotel Uno']);
        $this->actingAs($student);
        session()->put('practice.selected_company_id', $company->id);

        Livewire::test(StudentPracticeRequest::class)
            ->set('nss', '00000000000')
            ->set('accountNumber', '00000000')
            ->set('gradeGroup', '8A')
            ->set('startDate', '2026-11-01')
            ->set('endDate', '2027-01-31')
            ->set('practiceLocation', 'Colima')
            ->set('letterRecipient', 'Dirección')
            ->set('position', 'Auxiliar')
            ->set('acceptNonResponsiva', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('alumno.practice-request.show'));

        $this->assertDatabaseHas('practice_requests', [
            'user_id' => $student->id,
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'status' => PracticeRequestStatus::Pending->value,
        ]);
        $this->assertNotNull(PracticeRequest::query()->firstOrFail()->non_responsiva_accepted_at);
        $this->assertSame('00000000000', PracticeRequest::query()->firstOrFail()->nss);
        $this->assertNotSame('00000000000', DB::table('practice_requests')->value('nss'));
        $this->assertSame('00000000', PracticeRequest::query()->firstOrFail()->account_number);
        $this->assertNotSame('00000000', DB::table('practice_requests')->value('account_number'));
        $this->assertNull(session('practice.selected_company_id'));
    }

    public function test_request_requires_all_fields_valid_dates_active_selection_and_explicit_acceptance(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));

        Livewire::test(StudentPracticeRequest::class)
            ->set('ucolEmail', '')
            ->call('submit')
            ->assertHasErrors([
                'ucolEmail' => 'required',
                'nss' => 'required',
                'accountNumber' => 'required',
                'gradeGroup' => 'required',
                'startDate' => 'required',
                'practiceLocation' => 'required',
                'letterRecipient' => 'required',
                'position' => 'required',
                'endDate' => 'required',
                'acceptNonResponsiva' => 'accepted',
            ]);

        $company = Company::query()->create(['name' => 'Hotel Activo']);
        session()->put('practice.selected_company_id', $company->id);

        Livewire::test(StudentPracticeRequest::class)
            ->set('nss', '00000000000')
            ->set('accountNumber', '00000000')
            ->set('gradeGroup', '8A')
            ->set('startDate', '2026-11-01')
            ->set('endDate', '2026-11-01')
            ->set('practiceLocation', 'Colima')
            ->set('letterRecipient', 'Dirección')
            ->set('position', 'Auxiliar')
            ->set('acceptNonResponsiva', true)
            ->call('submit')
            ->assertHasErrors(['endDate' => 'after']);

        $this->assertDatabaseCount('practice_requests', 0);

        Livewire::test(StudentPracticeRequest::class)
            ->set('nss', '00000')
            ->set('accountNumber', '0000')
            ->set('gradeGroup', '8A')
            ->set('startDate', '2026-11-01')
            ->set('endDate', '2027-01-31')
            ->set('practiceLocation', 'Colima')
            ->set('letterRecipient', 'Dirección')
            ->set('position', 'Auxiliar')
            ->set('acceptNonResponsiva', true)
            ->call('submit')
            ->assertHasErrors(['nss' => 'digits', 'accountNumber' => 'digits']);
    }

    public function test_selected_company_must_still_be_active_when_request_is_submitted(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));
        $company = Company::query()->create(['name' => 'Hotel Temporal']);
        session()->put('practice.selected_company_id', $company->id);
        $component = Livewire::test(StudentPracticeRequest::class);
        $company->is_active = false;
        $company->save();

        $component
            ->set('nss', '00000000000')
            ->set('accountNumber', '00000000')
            ->set('gradeGroup', '8A')
            ->set('startDate', '2026-11-01')
            ->set('endDate', '2027-01-31')
            ->set('practiceLocation', 'Colima')
            ->set('letterRecipient', 'Dirección')
            ->set('position', 'Auxiliar')
            ->set('acceptNonResponsiva', true)
            ->call('submit')
            ->assertHasErrors('company');

        $this->assertDatabaseCount('practice_requests', 0);
    }

    public function test_student_cannot_submit_a_second_request_while_one_is_pending(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $company = Company::query()->create(['name' => 'Hotel Uno']);
        $existingRequest = $student->practiceRequests()->create([
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-10-01',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2026-12-01',
            'status' => PracticeRequestStatus::Pending,
            'non_responsiva_accepted_at' => now(),
        ]);
        $this->actingAs($student);
        session()->put('practice.selected_company_id', $company->id);

        Livewire::test(StudentPracticeRequest::class)
            ->set('nss', '00000000000')
            ->set('accountNumber', '00000000')
            ->set('gradeGroup', '8A')
            ->set('startDate', '2026-11-01')
            ->set('endDate', '2027-01-31')
            ->set('practiceLocation', 'Colima')
            ->set('letterRecipient', 'Dirección')
            ->set('position', 'Auxiliar')
            ->set('acceptNonResponsiva', true)
            ->call('submit')
            ->assertHasErrors('request');

        $this->assertSame($existingRequest->id, PracticeRequest::query()->sole()->id);
    }

    public function test_student_can_consult_only_their_own_practice_request(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $otherStudent = User::factory()->create(['role' => UserRole::Student]);
        $ownCompany = Company::query()->create(['name' => 'Empresa Propia']);
        $otherCompany = Company::query()->create(['name' => 'Empresa Ajena']);

        foreach ([[$student, $ownCompany], [$otherStudent, $otherCompany]] as [$owner, $company]) {
            $owner->practiceRequests()->create([
                'company_id' => $company->id,
                'ucol_email' => $owner->email,
                'nss' => '00000000000',
                'account_number' => '00000000',
                'grade_group' => '8A',
                'start_date' => '2026-10-01',
                'practice_location' => 'Colima',
                'letter_recipient' => 'Dirección',
                'position' => 'Auxiliar',
                'end_date' => '2026-12-01',
                'status' => PracticeRequestStatus::Returned,
                'non_responsiva_accepted_at' => now(),
            ]);
        }

        $this->actingAs($student)
            ->get(route('alumno.practice-request.show'))
            ->assertOk()
            ->assertSee('Empresa Propia')
            ->assertDontSee('Empresa Ajena');
    }

    public function test_submitted_request_is_read_only_and_letter_print_uses_current_date(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $company = Company::query()->create(['name' => 'Hotel Uno']);
        $student->practiceRequests()->create([
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-10-01',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2026-12-01',
            'status' => PracticeRequestStatus::Pending,
            'non_responsiva_accepted_at' => now(),
        ]);
        $this->actingAs($student);

        $this->get(route('alumno.practice-request.show'))
            ->assertOk()
            ->assertSee('Tu solicitud está en trámite y no se puede editar.')
            ->assertSee('Imprimir carta de no responsiva')
            ->assertSee(now()->locale('es')->translatedFormat('j \d\e F \d\e\l Y'));
    }

    public function test_student_can_edit_a_request_only_after_it_is_returned(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);
        $company = Company::query()->create(['name' => 'Hotel Devuelto']);
        $request = $student->practiceRequests()->create([
            'company_id' => $company->id,
            'ucol_email' => $student->email,
            'nss' => '00000000000',
            'account_number' => '00000000',
            'grade_group' => '8A',
            'start_date' => '2026-10-01',
            'practice_location' => 'Colima',
            'letter_recipient' => 'Dirección',
            'position' => 'Auxiliar',
            'end_date' => '2026-12-01',
            'status' => PracticeRequestStatus::Returned,
            'non_responsiva_accepted_at' => now(),
        ]);
        $this->actingAs($student);

        Livewire::test(StudentPracticeRequest::class)
            ->set('nss', '00000000000')
            ->set('accountNumber', '00000000')
            ->set('gradeGroup', '8B')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect(route('alumno.practice-request.show'));

        $this->assertDatabaseHas('practice_requests', [
            'id' => $request->id,
            'grade_group' => '8B',
            'status' => PracticeRequestStatus::Pending->value,
        ]);

        Livewire::test(StudentPracticeRequest::class)
            ->set('gradeGroup', '9C')
            ->call('submit')
            ->assertHasErrors();

        $this->assertDatabaseHas('practice_requests', [
            'id' => $request->id,
            'grade_group' => '8B',
        ]);
    }

    public function test_users_without_student_role_cannot_access_the_request(): void
    {
        foreach ([UserRole::Coordinator, UserRole::Supervisor, UserRole::Tutor] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get(route('alumno.practice-request.show'))
                ->assertForbidden();
        }
    }
}
