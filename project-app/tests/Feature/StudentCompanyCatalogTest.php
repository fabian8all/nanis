<?php

namespace Tests\Feature;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Livewire\Student\Companies\Index as StudentCompanyCatalog;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class StudentCompanyCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_sees_active_companies_by_recent_update_with_contact_details(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));
        $zeta = Company::query()->create([
            'name' => 'Hotel Zeta',
            'address' => 'Av. Central 20',
            'phone' => '3125550100',
            'email' => 'zeta@example.test',
            'contact_person' => 'Contacto Zeta',
        ]);
        $inactiveCompany = Company::query()->create(['name' => 'Hotel Inactivo']);
        $inactiveCompany->is_active = false;
        $inactiveCompany->save();
        $alpha = Company::query()->create([
            'name' => 'Hotel Alfa',
            'address' => 'Calle Norte 10',
            'phone' => '3125550101',
            'email' => 'alfa@example.test',
            'contact_person' => 'Contacto Alfa',
        ]);
        $sameUpdate = Company::query()->create(['name' => 'Hotel Empate']);
        DB::table('companies')->where('id', $zeta->id)->update(['updated_at' => '2026-06-02 12:00:00']);
        DB::table('companies')->where('id', $alpha->id)->update(['updated_at' => '2026-06-01 12:00:00']);
        DB::table('companies')->where('id', $sameUpdate->id)->update(['updated_at' => '2026-06-02 12:00:00']);

        $this->get(route('alumno.companies.index'))
            ->assertOk()
            ->assertSeeInOrder(['Hotel Empate', 'Hotel Zeta', 'Hotel Alfa'])
            ->assertDontSee('Hotel Inactivo')
            ->assertSee('Calle Norte 10')
            ->assertSee('3125550101')
            ->assertSee('mailto:alfa@example.test')
            ->assertSee('Contacto Alfa')
            ->assertSee('href="'.route('alumno.companies.index').'"', false);

        $this->assertDatabaseHas('companies', [
            'id' => $zeta->id,
            'is_active' => true,
        ]);
    }

    public function test_student_can_select_and_change_an_active_company_in_the_session(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));
        $firstCompany = Company::query()->create(['name' => 'Hotel Uno']);
        $secondCompany = Company::query()->create(['name' => 'Hotel Dos']);

        Livewire::test(StudentCompanyCatalog::class)
            ->call('select', $firstCompany->id)
            ->assertSee('Seleccionaste Hotel Uno.')
            ->assertSee('Empresa seleccionada');

        $this->assertSame($firstCompany->id, session('practice.selected_company_id'));

        Livewire::test(StudentCompanyCatalog::class)
            ->call('select', $secondCompany->id)
            ->assertSee('Seleccionaste Hotel Dos.')
            ->assertSee('Empresa seleccionada');

        $this->assertSame($secondCompany->id, session('practice.selected_company_id'));
        $this->assertDatabaseCount('companies', 2);
    }

    public function test_search_paginates_active_company_options_without_clearing_a_selection(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));
        $selected = Company::query()->create([
            'name' => 'Hotel de búsqueda',
            'contact_person' => 'Contacto buscado',
        ]);
        foreach (range(1, 16) as $index) {
            Company::query()->create(['name' => "Hotel {$index}"]);
        }

        Livewire::test(StudentCompanyCatalog::class)
            ->call('select', $selected->id)
            ->call('nextPage')
            ->assertSet('paginators.page', 2)
            ->set('search', 'Contacto buscado')
            ->assertSet('paginators.page', 1)
            ->assertViewHas('companies', fn ($companies) => $companies->total() === 1)
            ->assertSee('Hotel de búsqueda')
            ->assertSee('Empresa seleccionada');

        $this->assertSame($selected->id, session('practice.selected_company_id'));
    }

    public function test_catalog_remains_available_but_selection_is_blocked_for_pending_approved_and_completed_requests(): void
    {
        foreach ([
            PracticeRequestStatus::Pending,
            PracticeRequestStatus::Approved,
            PracticeRequestStatus::Completed,
        ] as $status) {
            $student = User::factory()->create(['role' => UserRole::Student]);
            $company = Company::query()->create(['name' => "Hotel {$status->value}"]);
            $otherCompany = Company::query()->create(['name' => 'Hotel Otra']);
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
                'status' => $status,
                'non_responsiva_accepted_at' => now(),
            ]);

            $this->actingAs($student)
                ->get(route('alumno.companies.index'))
                ->assertOk()
                ->assertSee($company->name)
                ->assertSee('no seleccionar otra empresa')
                ->assertDontSee('wire:click="select(', false);

            Livewire::test(StudentCompanyCatalog::class)
                ->call('select', $otherCompany->id)
                ->assertHasErrors('request');
        }
    }

    public function test_student_cannot_select_an_inactive_company(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));
        $company = Company::query()->create(['name' => 'Hotel Cerrado']);
        $company->is_active = false;
        $company->save();

        Livewire::test(StudentCompanyCatalog::class)
            ->call('select', $company->id)
            ->assertStatus(404);

        $this->assertNull(session('practice.selected_company_id'));
    }

    public function test_selection_is_cleared_if_company_becomes_inactive(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Student]));
        $company = Company::query()->create(['name' => 'Hotel Deshabilitado']);
        session()->put('practice.selected_company_id', $company->id);
        $company->is_active = false;
        $company->save();

        $this->get(route('alumno.companies.index'))
            ->assertOk()
            ->assertSessionHas('status', 'La empresa seleccionada ya no está disponible. Elige otra empresa con convenio vigente.');

        $this->assertNull(session('practice.selected_company_id'));
    }

    public function test_catalog_is_for_students_and_home_links_to_the_catalog(): void
    {
        $coordinator = User::factory()->create(['role' => UserRole::Coordinator]);

        $this->actingAs($coordinator)
            ->get(route('alumno.companies.index'))
            ->assertForbidden();

        $student = User::factory()->create(['role' => UserRole::Student]);

        $this->actingAs($student)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Consultar empresas con convenio')
            ->assertSee(route('alumno.companies.index'));
    }

    public function test_student_can_log_out_from_the_catalog(): void
    {
        $student = User::factory()->create(['role' => UserRole::Student]);

        $this->withoutMiddleware(PreventRequestForgery::class)
            ->actingAs($student)
            ->post(route('alumno.logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
