<?php

namespace App\Livewire\Supervisor\PracticePlans;

use App\Enums\PracticeAcceptanceStatus;
use App\Models\PracticePlanVersion;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeSupervisor();

        return view('livewire.supervisor.practice-plans.index', [
            'versions' => PracticePlanVersion::query()
                ->whereHas('practicePlan.practiceRequest.acceptanceSubmissions', fn ($query) => $query
                    ->where('supervisor_id', auth()->id())
                    ->where('status', PracticeAcceptanceStatus::Accepted->value))
                ->with([
                    'practicePlan.practiceRequest.student:id,name',
                    'practicePlan.practiceRequest.company:id,name',
                    'activities',
                ])
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Planes de trabajo']);
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(auth()->user()?->isSupervisor() && auth()->user()?->email_verified_at, 403);
    }
}
