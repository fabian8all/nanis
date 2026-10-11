<?php

namespace App\Livewire\Coordinator\PracticeRequests;

use App\Enums\PracticeRequestStatus;
use App\Models\PracticeRequest;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function mount(): void
    {
        $this->authorizeCoordinator();
    }

    public function render(): View
    {
        $this->authorizeCoordinator();

        return view('livewire.coordinator.practice-requests.index', [
            'requests' => PracticeRequest::query()
                ->select(['id', 'user_id', 'company_id', 'grade_group', 'start_date', 'end_date', 'status', 'created_at'])
                ->with(['student:id,name,email', 'company:id,name'])
                ->where('status', PracticeRequestStatus::Pending)
                ->oldest('created_at')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Solicitudes de práctica']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
