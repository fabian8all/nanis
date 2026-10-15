<?php

namespace App\Livewire\Supervisor\PracticeLetters;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Models\PracticeLetter;
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
        $this->authorizeSupervisor();

        $query = PracticeLetter::query()
            ->whereHas('practiceRequest', fn ($query) => $query
                ->where('company_id', auth()->user()->company_id)
                ->where('status', PracticeRequestStatus::Approved->value)
                ->whereDoesntHave('acceptanceSubmissions', fn ($submissions) => $submissions
                    ->where('status', PracticeAcceptanceStatus::Accepted->value)))
            ->with([
                'practiceRequest' => fn ($query) => $query->select([
                    'id',
                    'company_id',
                    'user_id',
                    'status',
                ]),
                'practiceRequest.student:id,name',
                'practiceRequest.latestAcceptanceSubmission',
                'practiceRequest.acceptanceSubmissions' => fn ($query) => $query->with('reviewer:id,name')->orderBy('version'),
            ]);
        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query
                    ->where('folio', 'like', "%{$search}%")
                    ->orWhereHas('practiceRequest.student', fn ($student) => $student->where('name', 'like', "%{$search}%"));
            });
        }

        $letters = $query
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->sort(function (PracticeLetter $left, PracticeLetter $right): int {
                $leftSubmissionUpdate = $left->practiceRequest->acceptanceSubmissions
                    ->max(fn ($submission): ?int => $submission->updated_at?->getTimestamp()) ?? 0;
                $rightSubmissionUpdate = $right->practiceRequest->acceptanceSubmissions
                    ->max(fn ($submission): ?int => $submission->updated_at?->getTimestamp()) ?? 0;
                $leftModifiedAt = max($left->updated_at->getTimestamp(), $leftSubmissionUpdate);
                $rightModifiedAt = max($right->updated_at->getTimestamp(), $rightSubmissionUpdate);
                $modifiedOrder = $rightModifiedAt <=> $leftModifiedAt;

                return $modifiedOrder !== 0
                    ? $modifiedOrder
                    : $right->id <=> $left->id;
            })->values();

        return view('livewire.supervisor.practice-letters.index', [
            'letters' => $this->paginateCollection($letters),
        ])->layout('layouts.app', ['title' => 'Cartas pendientes de aceptación']);
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

    private function authorizeSupervisor(): void
    {
        abort_unless(auth()->user()?->isSupervisor() && auth()->user()?->email_verified_at !== null, 403);
    }
}
