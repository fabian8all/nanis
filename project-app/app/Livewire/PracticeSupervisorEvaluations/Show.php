<?php

namespace App\Livewire\PracticeSupervisorEvaluations;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Models\PracticeSupervisorEvaluation;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $evaluationId;

    public function mount(PracticeSupervisorEvaluation $practiceSupervisorEvaluation): void
    {
        $this->evaluationId = $practiceSupervisorEvaluation->id;
        $this->authorizeViewer();
    }

    public function render(): View
    {
        $evaluation = PracticeSupervisorEvaluation::query()
            ->with([
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'supervisor:id,name',
                'acceptanceSubmission',
                'rubricVersion',
                'criteria',
            ])
            ->findOrFail($this->evaluationId);
        $this->authorizeViewer($evaluation);

        return view('livewire.practice-supervisor-evaluations.show', [
            'evaluation' => $evaluation,
        ])->layout('layouts.app', ['title' => 'Resultado de evaluación del supervisor']);
    }

    private function authorizeViewer(?PracticeSupervisorEvaluation $evaluation = null): void
    {
        $evaluation ??= PracticeSupervisorEvaluation::query()->findOrFail($this->evaluationId);
        $user = auth()->user();
        abort_unless($user, 403);
        $canView = match ($user->role) {
            UserRole::Coordinator => true,
            UserRole::Supervisor => $user->isSupervisor()
                && $user->email_verified_at !== null
                && $evaluation->supervisor_id === $user->id,
            UserRole::Student => PracticeRequest::query()
                ->whereKey($evaluation->practice_request_id)
                ->where('user_id', $user->id)
                ->exists(),
            default => false,
        };
        abort_unless($canView, 404);
        abort_unless($evaluation->status === PracticeEvaluationStatus::Submitted, 404);
    }
}
