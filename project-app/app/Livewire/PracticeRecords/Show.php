<?php

namespace App\Livewire\PracticeRecords;

use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Services\PracticeTrackingSummary;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $practiceRequestId;

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->practiceRequestId = $practiceRequest->id;
        $this->authorizeViewer($practiceRequest);
    }

    public function render(PracticeTrackingSummary $summary): View
    {
        $practiceRequest = PracticeRequest::query()
            ->with([
                'student:id,name',
                'company:id,name',
                'practiceReport.approvedVersion.attachments',
                'practiceEvaluation',
                'practiceSupervisorEvaluation',
                'terminationLetter',
            ])
            ->findOrFail($this->practiceRequestId);
        $this->authorizeViewer($practiceRequest);
        $requirements = $summary->closureRequirements($practiceRequest);

        return view('livewire.practice-records.show', [
            'practiceRequest' => $practiceRequest,
            'approvedReport' => $practiceRequest->practiceReport?->approvedVersion,
            'requirements' => $requirements,
            'isComplete' => collect($requirements)->every(fn (array $item): bool => $item['complete']),
            'isCoordinator' => auth()->user()?->role === UserRole::Coordinator,
        ])->layout('layouts.app', ['title' => 'Expediente de práctica']);
    }

    private function authorizeViewer(PracticeRequest $practiceRequest): void
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $canView = $user->role === UserRole::Coordinator
            || ($user->role === UserRole::Student && $practiceRequest->user_id === $user->id);

        abort_unless($canView, 404);
    }
}
