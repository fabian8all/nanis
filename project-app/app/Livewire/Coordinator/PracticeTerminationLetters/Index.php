<?php

namespace App\Livewire\Coordinator\PracticeTerminationLetters;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
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
            'practices' => PracticeRequest::query()
                ->where('status', PracticeRequestStatus::Approved)
                ->whereHas('practiceReport.approvedVersion', fn ($query) => $query
                    ->where('status', PracticeReportStatus::Approved))
                ->whereHas('practiceEvaluation', fn ($query) => $query
                    ->where('status', PracticeEvaluationStatus::Submitted))
                ->whereHas('practiceSupervisorEvaluation', fn ($query) => $query
                    ->where('status', PracticeEvaluationStatus::Submitted))
                ->whereDoesntHave('terminationLetterIssuance')
                ->with([
                    'student:id,name',
                    'company:id,name',
                ])
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(20),
        ])->layout('layouts.app', ['title' => 'Cartas de término']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);
    }
}
