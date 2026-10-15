<?php

namespace App\Livewire\Tutor\PracticeEvaluations;

use App\Enums\PracticeReportStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
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
        $this->authorizeTutor();

        $query = PracticeRequest::query()
            ->where('tutor_id', auth()->id())
            ->whereHas('practiceReport', fn ($query) => $query
                ->whereHas('approvedVersion', fn ($version) => $version
                    ->where('status', PracticeReportStatus::Approved->value)))
            ->with([
                'student:id,name',
                'company:id,name',
                'practiceReport.approvedVersion',
                'practiceEvaluation',
            ]);
        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query
                    ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%"));
            });
        }

        $requests = $query
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
            })->values();

        return view('livewire.tutor.practice-evaluations.index', [
            'requests' => $this->paginateCollection($requests),
        ])->layout('layouts.app', ['title' => 'Evaluaciones de tutor']);
    }

    private function paginateCollection(Collection $items): LengthAwarePaginator
    {
        $perPage = 15;
        $page = Paginator::resolveCurrentPage();

        return new Paginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()],
        );
    }

    private function authorizeTutor(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Tutor, 403);
    }
}
