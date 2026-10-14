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
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->get()
                ->sort(function (PracticeRequest $left, PracticeRequest $right): int {
                    $leftModifiedAt = max(
                        $left->updated_at->getTimestamp(),
                        $left->latestAcceptanceSubmission->updated_at->getTimestamp(),
                        $left->practicePlan->latestVersion->updated_at->getTimestamp(),
                        $left->practiceSupervisorEvaluation?->updated_at?->getTimestamp() ?? 0,
                    );
                    $rightModifiedAt = max(
                        $right->updated_at->getTimestamp(),
                        $right->latestAcceptanceSubmission->updated_at->getTimestamp(),
                        $right->practicePlan->latestVersion->updated_at->getTimestamp(),
                        $right->practiceSupervisorEvaluation?->updated_at?->getTimestamp() ?? 0,
                    );
                    $modifiedOrder = $rightModifiedAt <=> $leftModifiedAt;

                    return $modifiedOrder !== 0
                        ? $modifiedOrder
                        : $right->id <=> $left->id;
                })->values(),
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
