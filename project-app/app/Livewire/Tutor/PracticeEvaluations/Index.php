<?php

namespace App\Livewire\Tutor\PracticeEvaluations;

use App\Enums\PracticeReportStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeTutor();

        return view('livewire.tutor.practice-evaluations.index', [
            'requests' => PracticeRequest::query()
                ->where('tutor_id', auth()->id())
                ->whereHas('practiceReport', fn ($query) => $query
                    ->whereHas('approvedVersion', fn ($version) => $version
                        ->where('status', PracticeReportStatus::Approved->value)))
                ->with([
                    'student:id,name',
                    'company:id,name',
                    'practiceReport.approvedVersion',
                    'practiceEvaluation',
                ])
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->get()
                ->sort(function (PracticeRequest $left, PracticeRequest $right): int {
                    $leftModifiedAt = max(
                        $left->updated_at->getTimestamp(),
                        $left->practiceReport->approvedVersion->updated_at->getTimestamp(),
                        $left->practiceEvaluation?->updated_at?->getTimestamp() ?? 0,
                    );
                    $rightModifiedAt = max(
                        $right->updated_at->getTimestamp(),
                        $right->practiceReport->approvedVersion->updated_at->getTimestamp(),
                        $right->practiceEvaluation?->updated_at?->getTimestamp() ?? 0,
                    );
                    $modifiedOrder = $rightModifiedAt <=> $leftModifiedAt;

                    return $modifiedOrder !== 0
                        ? $modifiedOrder
                        : $right->id <=> $left->id;
                })->values(),
        ])->layout('layouts.app', ['title' => 'Evaluaciones de tutor']);
    }

    private function authorizeTutor(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Tutor, 403);
    }
}
