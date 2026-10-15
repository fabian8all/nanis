<?php

namespace App\Livewire\Supervisor\PracticeSurveys;

use App\Enums\PracticeSurveyRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeSurveyRequest;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $this->authorizeSupervisor();

        $query = PracticeSurveyRequest::query()
            ->where('supervisor_id', auth()->id())
            ->whereIn('status', [
                PracticeSurveyRequestStatus::Requested,
                PracticeSurveyRequestStatus::Responded,
            ])
            ->with(['practiceRequest.student:id,name', 'practiceRequest.company:id,name']);
        $search = trim($this->search);
        if ($search !== '') {
            $query->whereHas('practiceRequest', fn ($request) => $request
                ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%")));
        }

        return view('livewire.supervisor.practice-surveys.index', [
            'requests' => $query
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Encuestas pendientes']);
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(
            auth()->user()?->role === UserRole::Supervisor
            && auth()->user()?->isSupervisor()
            && auth()->user()?->email_verified_at !== null,
            403,
        );
    }
}
