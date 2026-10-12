<?php

namespace App\Livewire\Coordinator\PracticeEvaluations;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\UserRole;
use App\Models\PracticeEvaluation;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);

        return view('livewire.coordinator.practice-evaluations.index', [
            'evaluations' => PracticeEvaluation::query()
                ->where('status', PracticeEvaluationStatus::Submitted->value)
                ->with(['practiceRequest.student:id,name', 'practiceRequest.company:id,name', 'tutor:id,name'])
                ->orderByDesc('submitted_at')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Evaluaciones de tutor']);
    }
}
