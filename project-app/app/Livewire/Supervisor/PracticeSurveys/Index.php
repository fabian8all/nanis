<?php

namespace App\Livewire\Supervisor\PracticeSurveys;

use App\Enums\PracticeSurveyRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeSurveyRequest;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeSupervisor();

        return view('livewire.supervisor.practice-surveys.index', [
            'requests' => PracticeSurveyRequest::query()
                ->where('supervisor_id', auth()->id())
                ->where('status', PracticeSurveyRequestStatus::Requested)
                ->with(['practiceRequest.student:id,name', 'practiceRequest.company:id,name'])
                ->orderByDesc('requested_at')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Encuestas pendientes']);
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(
            auth()->user()?->role === UserRole::Supervisor
            && auth()->user()?->isSupervisor()
            && auth()->user()?->email_verified_at !== null,
            403,
        );
    }
}
