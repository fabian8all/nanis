<?php

namespace App\Livewire\Coordinator\PracticeSupervisorEvaluations;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\UserRole;
use App\Models\PracticeSupervisorEvaluation;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);

        return view('livewire.coordinator.practice-supervisor-evaluations.index', [
            'evaluations' => PracticeSupervisorEvaluation::query()
                ->where('status', PracticeEvaluationStatus::Submitted->value)
                ->with([
                    'practiceRequest.student:id,name',
                    'practiceRequest.company:id,name',
                    'supervisor:id,name',
                ])
                ->orderByDesc('submitted_at')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Evaluaciones finales del supervisor']);
    }
}
