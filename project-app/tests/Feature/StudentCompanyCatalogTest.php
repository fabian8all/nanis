<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Student\Companies\Index as StudentCompanyCatalog;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StudentCompanyCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_sees_only_active_companies_in_name_order_with_contact_details(): void
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
        Company::query()->create([
            'name' => 'Hotel Alfa',
            'address' => 'Calle Norte 10',
            'phone' => '3125550101',
            'email' => 'alfa@example.test',
            'contact_person' => 'Contacto Alfa',
        ]);

        $this->get(route('alumno.companies.index'))
            ->assertOk()
            ->assertSeeInOrder(['Hotel Alfa', 'Hotel Zeta'])
            ->assertDontSee('Hotel Inactivo')
            ->assertSee('Calle Norte 10')
            ->assertSee('3125550101')
            ->assertSee('mailto:alfa@example.test')
            ->assertSee('Contacto Alfa');

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
