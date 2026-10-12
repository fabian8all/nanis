<?php

namespace App\Livewire\Supervisor\PracticeEvaluations;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeSupervisor();

        return view('livewire.supervisor.practice-evaluations.index', [
            'requests' => PracticeRequest::query()
                ->where('status', PracticeRequestStatus::Approved->value)
                ->whereHas('latestAcceptanceSubmission', fn ($query) => $query
                    ->where('supervisor_id', auth()->id())
                    ->where('status', PracticeAcceptanceStatus::Accepted->value))
                ->whereHas('practicePlan.latestVersion', fn ($query) => $query
                    ->where('status', PracticePlanStatus::Approved->value))
                ->with([
                    'student:id,name',
                    'company:id,name',
                    'latestAcceptanceSubmission',
                    'practicePlan.latestVersion',
                    'practiceSupervisorEvaluation',
                ])
                ->orderByDesc('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Evaluaciones finales del supervisor']);
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
