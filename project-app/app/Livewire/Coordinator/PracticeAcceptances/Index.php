<?php

namespace App\Livewire\Coordinator\PracticeAcceptances;

use App\Enums\PracticeAcceptanceStatus;
use App\Models\PracticeAcceptanceSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $pendingSearch = '';

    public string $historySearch = '';

    public function updatedPendingSearch(): void
    {
        $this->resetPage('pendingPage');
    }

    public function updatedHistorySearch(): void
    {
        $this->resetPage('historyPage');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
        $pending = PracticeAcceptanceSubmission::query()
            ->where('status', PracticeAcceptanceStatus::PendingReview->value)
            ->with([
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'practiceRequest.letter:id,practice_request_id,folio',
                'supervisor:id,name,email',
            ]);
        $history = PracticeAcceptanceSubmission::query()
            ->whereIn('status', [
                PracticeAcceptanceStatus::Returned->value,
                PracticeAcceptanceStatus::Accepted->value,
            ])
            ->with([
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'practiceRequest.letter:id,practice_request_id,folio',
                'supervisor:id,name,email',
            ]);
        $this->applySearch($pending, $this->pendingSearch);
        $this->applySearch($history, $this->historySearch);

        return view('livewire.coordinator.practice-acceptances.index', [
            'submissions' => $pending
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15, ['*'], 'pendingPage'),
            'history' => $history
                ->orderByDesc('reviewed_at')
                ->orderByDesc('id')
                ->paginate(15, ['*'], 'historyPage'),
        ])->layout('layouts.app', ['title' => 'Cartas de aceptación por revisar']);
    }

    private function applySearch(Builder $query, string $search): void
    {
        $search = trim($search);
        if ($search === '') {
            return;
        }

        $query->where(function ($query) use ($search): void {
            $query
                ->whereHas('practiceRequest.letter', fn ($letter) => $letter->where('folio', 'like', "%{$search}%"))
                ->orWhereHas('practiceRequest.student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                ->orWhereHas('practiceRequest.company', fn ($company) => $company->where('name', 'like', "%{$search}%"))
                ->orWhereHas('supervisor', fn ($supervisor) => $supervisor
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
        });
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
