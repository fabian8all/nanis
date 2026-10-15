<?php

namespace App\Livewire\Coordinator\PracticeEvaluations;

use App\Enums\PracticeEvaluationStatus;
use App\Enums\UserRole;
use App\Models\PracticeEvaluation;
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
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);
        $query = PracticeEvaluation::query()
            ->where('status', PracticeEvaluationStatus::Submitted->value)
            ->with(['practiceRequest.student:id,name', 'practiceRequest.company:id,name', 'tutor:id,name']);
        $search = trim($this->search);

        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query
                    ->whereHas('practiceRequest.student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('practiceRequest.company', fn ($company) => $company->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tutor', fn ($tutor) => $tutor->where('name', 'like', "%{$search}%"));
            });
        }

        return view('livewire.coordinator.practice-evaluations.index', [
            'evaluations' => $query
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Evaluaciones de tutor']);
    }
}
