<?php

namespace App\Livewire\Supervisor\PracticePlans;

use App\Enums\PracticeAcceptanceStatus;
use App\Models\PracticePlanVersion;
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

        $query = PracticePlanVersion::query()
            ->whereHas('practicePlan.practiceRequest.acceptanceSubmissions', fn ($query) => $query
                ->where('supervisor_id', auth()->id())
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->with([
                'practicePlan.practiceRequest.student:id,name',
                'practicePlan.practiceRequest.company:id,name',
                'activities',
            ]);
        $search = trim($this->search);
        if ($search !== '') {
            $query->whereHas('practicePlan.practiceRequest', fn ($request) => $request
                ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%")));
        }

        return view('livewire.supervisor.practice-plans.index', [
            'versions' => $query
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Planes de trabajo']);
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(auth()->user()?->isSupervisor() && auth()->user()?->email_verified_at, 403);
    }
}
