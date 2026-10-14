<?php

namespace App\Livewire\Companies;

use App\Models\Company;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    public bool $showForm = false;

    public string $search = '';

    public string $name = '';

    public string $address = '';

    public string $phone = '';

    public string $email = '';

    public string $contactPerson = '';

    public function mount(): void
    {
        $this->authorizeCoordinator();
    }

    public function save(): void
    {
        $this->authorizeCoordinator();

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'contactPerson' => ['nullable', 'string', 'max:255'],
        ]);

        $companyData = [
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'contact_person' => $data['contactPerson'],
        ];

        if ($this->editingId === null) {
            Company::create($companyData);
            session()->flash('status', 'La empresa fue agregada al catálogo.');
        } else {
            Company::query()->findOrFail($this->editingId)->update($companyData);
            session()->flash('status', 'Los datos de la empresa fueron actualizados.');
        }

        $this->resetForm();
    }

    public function startCreate(): void
    {
        $this->authorizeCoordinator();
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $companyId): void
    {
        $this->authorizeCoordinator();

        $this->resetForm();
        $company = Company::query()->findOrFail($companyId);
        $this->editingId = $company->id;
        $this->showForm = true;
        $this->name = $company->name;
        $this->address = $company->address ?? '';
        $this->phone = $company->phone ?? '';
        $this->email = $company->email ?? '';
        $this->contactPerson = $company->contact_person ?? '';
    }

    public function toggleActive(int $companyId): void
    {
        $this->authorizeCoordinator();

        $company = Company::query()->findOrFail($companyId);
        $company->is_active = ! $company->is_active;
        $company->save();

        session()->flash(
            'status',
            $company->is_active
                ? 'La empresa volvió a estar disponible para nuevas solicitudes.'
                : 'La empresa fue ocultada para nuevas solicitudes.',
        );
    }

    public function cancelEdit(): void
    {
        $this->authorizeCoordinator();
        $this->resetForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $this->authorizeCoordinator();

        $query = Company::query()->orderBy('name')->orderBy('id');
        $search = trim($this->search);

        if ($search !== '') {
            $query
                ->where('is_active', true)
                ->where('name', 'like', "%{$search}%");
        }

        return view('livewire.companies.index', [
            'companies' => $query->paginate(20),
        ])->layout('layouts.app', ['title' => 'Catálogo de empresas']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->showForm = false;
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->contactPerson = '';
        $this->resetValidation();
    }
}
