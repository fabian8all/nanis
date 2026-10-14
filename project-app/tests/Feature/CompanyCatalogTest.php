<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Companies\Index;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_can_create_and_update_a_company(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Coordinator]));

        Livewire::test(Index::class)
            ->assertDontSee('id="name"', false)
            ->call('startCreate')
            ->assertSee('id="name"', false)
            ->set('name', 'Hotel Ejemplo')
            ->set('address', 'Av. Central 123')
            ->set('phone', '3125550100')
            ->set('email', 'contacto@example.test')
            ->set('contactPerson', 'Persona de contacto')
            ->call('save')
            ->assertHasNoErrors();

        $company = Company::query()->firstOrFail();

        $this->assertSame('Hotel Ejemplo', $company->name);
        $this->assertTrue($company->is_active);

        Livewire::test(Index::class)
            ->call('edit', $company->id)
            ->assertSee('Editar empresa')
            ->set('name', 'Hotel Actualizado')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Hotel Actualizado',
        ]);
    }

    public function test_coordinator_can_cancel_creation_and_editing_without_saving_changes(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Coordinator]));
        $company = Company::query()->create(['name' => 'Hotel Ejemplo']);

        Livewire::test(Index::class)
            ->call('startCreate')
            ->set('name', 'Hotel no guardado')
            ->call('cancelEdit')
            ->assertSet('showForm', false)
            ->assertDontSee('id="name"', false)
            ->call('edit', $company->id)
            ->set('name', 'Cambio no guardado')
            ->call('cancelEdit')
            ->assertSet('showForm', false)
            ->assertDontSee('id="name"', false);

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Hotel Ejemplo',
        ]);
    }

    public function test_coordinator_can_hide_and_reactivate_a_company_without_deleting_it(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Coordinator]));
        $company = Company::query()->create(['name' => 'Hotel Ejemplo']);

        Livewire::test(Index::class)
            ->call('toggleActive', $company->id)
            ->assertSee('La empresa fue ocultada para nuevas solicitudes.');

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'is_active' => false,
        ]);

        Livewire::test(Index::class)
            ->call('toggleActive', $company->id)
            ->assertSee('La empresa volvió a estar disponible para nuevas solicitudes.');

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'is_active' => true,
        ]);
    }

    public function test_company_email_must_be_valid_when_provided(): void
    {
        $this->actingAs(User::factory()->create(['role' => UserRole::Coordinator]));

        Livewire::test(Index::class)
            ->set('name', 'Hotel Ejemplo')
            ->set('email', 'not-an-email')
            ->call('save')
            ->assertHasErrors(['email' => 'email']);

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_catalog_cannot_be_used_without_coordinator_access(): void
    {
        $this->get(route('coordinacion.companies.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => UserRole::Student]))
            ->get(route('coordinacion.companies.index'))
            ->assertForbidden();
    }
}
