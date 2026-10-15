<?php

namespace App\Livewire\Coordinator\PracticePlans;

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
        $this->authorizeCoordinator();

        $query = PracticePlanVersion::query()
            ->with([
                'practicePlan.practiceRequest.student:id,name',
                'practicePlan.practiceRequest.company:id,name',
            ]);
        $search = trim($this->search);

        if ($search !== '') {
            $query->whereHas('practicePlan.practiceRequest', fn ($request) => $request
                ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%")));
        }

        return view('livewire.coordinator.practice-plans.index', [
            'versions' => $query
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Planes de trabajo']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
