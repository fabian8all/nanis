<?php

namespace App\Livewire\Coordinator\PracticeTerminationLetters;

use App\Enums\UserRole;
use App\Models\PracticeTerminationLetter;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render(): View
    {
        $this->authorizeCoordinator();

        return view('livewire.coordinator.practice-termination-letters.index', [
            'letters' => PracticeTerminationLetter::query()
                ->with([
                    'practiceRequest.student:id,name',
                    'practiceRequest.company:id,name',
                ])
                ->latest('submitted_at')
                ->paginate(20),
        ])->layout('layouts.app', ['title' => 'Cartas de término']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);
    }
}
