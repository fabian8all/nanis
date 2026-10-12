<?php

namespace App\Livewire\PracticeEvaluations;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\UserRole;
use App\Models\PracticeEvaluation;
use App\Models\PracticeRequest;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $evaluationId;

    public function mount(PracticeEvaluation $practiceEvaluation): void
    {
        $this->evaluationId = $practiceEvaluation->id;
        $this->authorizeViewer();
    }

    public function render(): View
    {
        $evaluation = PracticeEvaluation::query()
            ->with([
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'tutor:id,name',
                'reportVersion',
                'rubricVersion',
                'criteria',
            ])
            ->findOrFail($this->evaluationId);
        $this->authorizeViewer($evaluation);

        return view('livewire.practice-evaluations.show', [
            'evaluation' => $evaluation,
        ])->layout('layouts.app', ['title' => 'Resultado de evaluación']);
    }

    private function authorizeViewer(?PracticeEvaluation $evaluation = null): void
    {
        $evaluation ??= PracticeEvaluation::query()->findOrFail($this->evaluationId);
        $user = auth()->user();
        abort_unless($user, 403);
        $canView = match ($user->role) {
            UserRole::Coordinator => true,
            UserRole::Tutor => $evaluation->tutor_id === $user->id,
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
