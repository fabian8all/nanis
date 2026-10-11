<?php

namespace App\Livewire\Coordinator\PracticePlans;

use App\Models\PracticePlanVersion;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeCoordinator();

        return view('livewire.coordinator.practice-plans.index', [
            'versions' => PracticePlanVersion::query()
                ->with([
                    'practicePlan.practiceRequest.student:id,name',
                    'practicePlan.practiceRequest.company:id,name',
                ])
                ->orderByDesc('submitted_at')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Planes de trabajo']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
