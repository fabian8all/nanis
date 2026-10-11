<?php

namespace App\Livewire\Student\Companies;

use App\Enums\UserRole;
use App\Models\Company;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    private const SELECTED_COMPANY_SESSION_KEY = 'practice.selected_company_id';

    public function boot(): void
    {
        $this->authorizeStudent();
    }

    public function select(int $companyId): void
    {
        $this->authorizeStudent();

        $company = Company::query()
            ->where('is_active', true)
            ->findOrFail($companyId);

        session()->put(self::SELECTED_COMPANY_SESSION_KEY, $company->id);
        session()->flash(
            'status',
            "Seleccionaste {$company->name}. La elección estará disponible para la solicitud durante esta sesión.",
        );
    }

    public function render(): View
    {
        $this->authorizeStudent();

        $companies = Company::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $selectedCompanyId = session()->get(self::SELECTED_COMPANY_SESSION_KEY);
        $selectedCompany = is_int($selectedCompanyId)
            ? $companies->firstWhere('id', $selectedCompanyId)
            : null;

        if ($selectedCompanyId !== null && $selectedCompany === null) {
            session()->forget(self::SELECTED_COMPANY_SESSION_KEY);
            session()->flash(
                'status',
                'La empresa seleccionada ya no está disponible. Elige otra empresa con convenio vigente.',
            );
        }

        return view('livewire.student.companies.index', [
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
        ])->layout('layouts.app', ['title' => 'Empresas con convenio']);
    }

    private function authorizeStudent(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Student, 403);
    }
}
