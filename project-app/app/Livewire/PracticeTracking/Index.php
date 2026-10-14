<?php

namespace App\Livewire\PracticeTracking;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Services\PracticeTrackingSummary;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $stateFilter = '';

    public string $stageFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStateFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStageFilter(): void
    {
        $this->resetPage();
    }

    public function render(PracticeTrackingSummary $summary): View
    {
        $user = auth()->user();
        abort_unless($user && in_array($user->role, [
            UserRole::Coordinator,
            UserRole::Student,
            UserRole::Supervisor,
            UserRole::Tutor,
        ], true), 403);

        $query = PracticeRequest::query()
            ->with([
                'student:id,name',
                'company:id,name',
                'letter:id,practice_request_id,folio',
                'latestAcceptanceSubmission',
                'acceptanceSubmissions:id,practice_request_id,supervisor_id,status,updated_at',
                'practicePlan.latestVersion',
                'practiceReport.latestVersion',
                'practiceReport.approvedVersion',
                'practiceEvaluation:id,practice_request_id,status',
                'practiceSupervisorEvaluation:id,practice_request_id,supervisor_id,status',
                'terminationLetterIssuance',
            ])
            ->when($user->role === UserRole::Student, fn (Builder $builder) => $builder->where('user_id', $user->id))
            ->when($user->role === UserRole::Tutor, fn (Builder $builder) => $builder->where('tutor_id', $user->id))
            ->when(
                $user->role === UserRole::Supervisor,
                fn (Builder $builder) => $builder
                    ->where('company_id', $user->company_id)
                    ->where(function (Builder $scope) use ($user): void {
                        $scope
                            ->whereDoesntHave('acceptanceSubmissions')
                            ->orWhereHas('acceptanceSubmissions', fn (Builder $subquery) => $subquery
                                ->where('supervisor_id', $user->id));
                    }),
            )
            ->when($user->role === UserRole::Coordinator && $this->search !== '', function (Builder $builder): void {
                $search = trim($this->search);
                $builder->where(function (Builder $searchQuery) use ($search): void {
                    $searchQuery
                        ->whereHas('student', fn (Builder $student) => $student->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('company', fn (Builder $company) => $company->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id');

        $requests = $query->get();
        $rows = $requests->map(function (PracticeRequest $request) use ($summary, $user): array {
            $stages = $summary->stages($request);
            $stageCollection = collect($stages);

            return [
                'request' => $request,
                'stages' => $stages,
                'headlineStage' => $stageCollection->first(
                    fn (array $stage): bool => $stage['responsible'] !== null,
                ) ?? $stageCollection->last(),
                'recordRoute' => match ($user->role) {
                    UserRole::Coordinator => route('coordinacion.practice-records.show', $request),
                    UserRole::Student => route('alumno.practice-records.show', $request),
                    UserRole::Tutor => route('tutor.practice-records.show', $request),
                    UserRole::Supervisor => $request->acceptanceSubmissions->contains(
                        fn ($submission): bool => $submission->supervisor_id === $user->id
                            && $submission->status === PracticeAcceptanceStatus::Accepted,
                    ) ? route('supervisor.practice-records.show', $request) : null,
                },
                'pending' => collect($stages)->filter(fn (array $stage): bool => $stage['responsible'] === $user->role->value
                    && ($stage['responsible_id'] === null || $stage['responsible_id'] === $user->id))->values(),
                'modifiedAt' => $this->latestModificationTimestamp($request),
            ];
        });

        if ($user->role === UserRole::Coordinator && $this->stageFilter !== '') {
            $rows = $rows->map(function (array $row): array {
                $row['stages'] = collect($row['stages'])->filter(
                    fn (array $stage): bool => $stage['label'] === $this->stageFilter,
                )->values()->all();

                return $row;
            })->filter(fn (array $row): bool => $row['stages'] !== []);
        }

        if ($user->role === UserRole::Coordinator && $this->stateFilter !== '') {
            $rows = $rows->map(function (array $row): array {
                $row['stages'] = collect($row['stages'])->filter(
                    fn (array $stage): bool => $stage['status'] === $this->stateFilter,
                )->values()->all();

                return $row;
            })->filter(fn (array $row): bool => $row['stages'] !== []);
        }

        $rows = $rows->sort(function (array $left, array $right): int {
            $modifiedOrder = $right['modifiedAt'] <=> $left['modifiedAt'];

            return $modifiedOrder !== 0
                ? $modifiedOrder
                : $right['request']->id <=> $left['request']->id;
        })->values();

        $pendingCount = $rows->sum(fn (array $row): int => $row['pending']->count());
        $paginated = $this->paginateRows($rows);

        return view('livewire.practice-tracking.index', [
            'rows' => $paginated,
            'pendingCount' => $pendingCount,
            'canFilter' => $user->role === UserRole::Coordinator,
        ])->layout('layouts.app', ['title' => 'Seguimiento de prácticas']);
    }

    private function paginateRows(Collection $rows): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage();
        $perPage = 15;

        return new Paginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ],
        );
    }

    private function latestModificationTimestamp(PracticeRequest $request): int
    {
        $timestamps = [
            $request->updated_at?->getTimestamp(),
            $request->letter?->updated_at?->getTimestamp(),
            $request->latestAcceptanceSubmission?->updated_at?->getTimestamp(),
            $request->acceptanceSubmissions->max(
                fn ($submission): ?int => $submission->updated_at?->getTimestamp(),
            ),
            $request->practicePlan?->latestVersion?->updated_at?->getTimestamp(),
            $request->practiceReport?->latestVersion?->updated_at?->getTimestamp(),
            $request->practiceReport?->approvedVersion?->updated_at?->getTimestamp(),
            $request->practiceEvaluation?->updated_at?->getTimestamp(),
            $request->practiceSupervisorEvaluation?->updated_at?->getTimestamp(),
            $request->terminationLetterIssuance?->updated_at?->getTimestamp(),
        ];

        return max(array_filter($timestamps, fn (?int $timestamp): bool => $timestamp !== null));
    }
}
