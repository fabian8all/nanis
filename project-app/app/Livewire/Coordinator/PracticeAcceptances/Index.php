<?php

namespace App\Livewire\Coordinator\PracticeAcceptances;

use App\Enums\PracticeAcceptanceStatus;
use App\Models\PracticeAcceptanceSubmission;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeCoordinator();

        return view('livewire.coordinator.practice-acceptances.index', [
            'submissions' => PracticeAcceptanceSubmission::query()
                ->where('status', PracticeAcceptanceStatus::PendingReview->value)
                ->with([
                    'practiceRequest.student:id,name',
                    'practiceRequest.company:id,name',
                    'practiceRequest.letter:id,practice_request_id,folio',
                    'supervisor:id,name,email',
                ])
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->get(),
            'history' => PracticeAcceptanceSubmission::query()
                ->whereIn('status', [
                    PracticeAcceptanceStatus::Returned->value,
                    PracticeAcceptanceStatus::Accepted->value,
                ])
                ->with([
                    'practiceRequest.student:id,name',
                    'practiceRequest.company:id,name',
                    'practiceRequest.letter:id,practice_request_id,folio',
                    'supervisor:id,name,email',
                ])
                ->orderByDesc('reviewed_at')
                ->orderByDesc('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Cartas de aceptación por revisar']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
