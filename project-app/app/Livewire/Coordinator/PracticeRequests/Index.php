<?php

namespace App\Livewire\Coordinator\PracticeRequests;

use App\Enums\PracticeRequestStatus;
use App\Models\PracticeRequest;
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

    public function mount(): void
    {
        $this->authorizeCoordinator();
    }

    public function render(): View
    {
        $this->authorizeCoordinator();

        $query = PracticeRequest::query()
            ->select(['id', 'user_id', 'company_id', 'grade_group', 'start_date', 'end_date', 'status', 'created_at'])
            ->with(['student:id,name,email', 'company:id,name'])
            ->where('status', PracticeRequestStatus::Pending);
        $search = trim($this->search);

        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query
                    ->whereHas('student', fn ($student) => $student
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%"))
                    ->orWhere('grade_group', 'like', "%{$search}%");
            });
        }

        return view('livewire.coordinator.practice-requests.index', [
            'requests' => $query
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Solicitudes de práctica']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
