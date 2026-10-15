<?php

namespace App\Livewire\Coordinator\PracticeReports;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\UserRole;
use App\Models\PracticeReportVersion;
use App\Models\PracticeRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public array $tutorAssignments = [];

    public string $search = '';

    public string $tutorSearch = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTutorSearch(): void
    {
        $this->resetPage('tutorsPage');
    }

    public function assignTutor(int $practiceRequestId): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            "tutorAssignments.{$practiceRequestId}" => ['required', 'integer', 'exists:users,id'],
        ]);
        $tutorId = $data['tutorAssignments'][$practiceRequestId];
        $tutor = User::query()->whereKey($tutorId)->where('role', UserRole::Tutor->value)->first();
        if (! $tutor) {
            throw ValidationException::withMessages([
                "tutorAssignments.{$practiceRequestId}" => 'Selecciona una cuenta con rol de tutor.',
            ]);
        }

        DB::transaction(function () use ($practiceRequestId, $tutor): void {
            $request = PracticeRequest::query()->lockForUpdate()->findOrFail($practiceRequestId);
            abort_unless($this->isEligible($request), 404);
            $hasSubmittedVersion = $request->practiceReport?->versions()
                ->whereNotNull('submitted_at')
                ->exists() ?? false;
            if ($hasSubmittedVersion && $request->tutor_id !== $tutor->id) {
                throw ValidationException::withMessages([
                    "tutorAssignments.{$practiceRequestId}" => 'No se puede cambiar el tutor después del primer envío del reporte.',
                ]);
            }
            $request->update(['tutor_id' => $tutor->id]);
        });
        session()->flash('status', 'El tutor quedó asignado a la práctica.');
    }

    public function reopen(int $reportId): void
    {
        $this->authorizeCoordinator();
        DB::transaction(function () use ($reportId): void {
            $version = PracticeReportVersion::query()
                ->whereHas('report.practiceRequest', fn ($query) => $query
                    ->whereHas('practicePlan.versions', fn ($plans) => $plans
                        ->where('status', PracticePlanStatus::Approved->value))
                    ->whereHas('acceptanceSubmissions', fn ($acceptances) => $acceptances
                        ->where('status', PracticeAcceptanceStatus::Accepted->value)))
                ->lockForUpdate()
                ->findOrFail($reportId);
            if ($version->status !== PracticeReportStatus::Approved) {
                throw ValidationException::withMessages([
                    'report' => 'Solo se puede reabrir una versión aprobada.',
                ]);
            }
            $report = $version->report()->lockForUpdate()->firstOrFail();
            if (
                $report->approved_version_id !== $version->id
                || (int) $report->versions()->max('version') !== $version->version
            ) {
                throw ValidationException::withMessages([
                    'report' => 'Solo se puede reabrir la versión aprobada vigente.',
                ]);
            }
            if ($report->versions()->where('status', PracticeReportStatus::Draft->value)->exists()) {
                throw ValidationException::withMessages([
                    'report' => 'Ya existe un borrador de reapertura para este reporte.',
                ]);
            }
            $draft = $report->versions()->create([
                ...$version->only([
                    'company_name', 'area', 'period', 'objectives', 'activities', 'outcomes', 'conclusions', 'recommendations',
                ]),
                'version' => (int) $report->versions()->max('version') + 1,
                'status' => PracticeReportStatus::Draft,
            ]);
            foreach ($version->attachments as $attachment) {
                $draft->attachments()->create($attachment->only([
                    'file_path', 'original_name', 'mime_type', 'file_size',
                ]));
            }
        });
        session()->flash('status', 'Se creó una nueva versión en borrador; la versión aprobada se conserva intacta.');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
        $query = PracticeRequest::query()
            ->whereHas('practicePlan.versions', fn ($query) => $query
                ->where('status', PracticePlanStatus::Approved->value))
            ->whereHas('acceptanceSubmissions', fn ($query) => $query
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->with([
                'student:id,name',
                'company:id,name',
                'tutor:id,name',
                'practiceReport.latestVersion',
                'practiceReport.versions' => fn ($query) => $query
                    ->with('attachments')
                    ->orderByDesc('updated_at')
                    ->orderByDesc('id'),
            ]);
        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($query) use ($search): void {
                $query
                    ->whereHas('student', fn ($student) => $student->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('tutor', fn ($tutor) => $tutor->where('name', 'like', "%{$search}%"));
            });
        }

        $requests = $query
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->sort(function (PracticeRequest $left, PracticeRequest $right): int {
                $leftModifiedAt = max(
                    $left->updated_at->getTimestamp(),
                    $left->practiceReport?->latestVersion?->updated_at?->getTimestamp() ?? 0,
                );
                $rightModifiedAt = max(
                    $right->updated_at->getTimestamp(),
                    $right->practiceReport?->latestVersion?->updated_at?->getTimestamp() ?? 0,
                );
                $modifiedOrder = $rightModifiedAt <=> $leftModifiedAt;

                return $modifiedOrder !== 0
                    ? $modifiedOrder
                    : $right->id <=> $left->id;
            })->values();
        $tutorsQuery = User::query()->where('role', UserRole::Tutor->value);
        $tutorSearch = trim($this->tutorSearch);
        if ($tutorSearch !== '') {
            $tutorsQuery->where(function ($query) use ($tutorSearch): void {
                $query
                    ->where('name', 'like', "%{$tutorSearch}%")
                    ->orWhere('email', 'like', "%{$tutorSearch}%");
            });
        }
        $tutors = $tutorsQuery
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(15, ['id', 'name'], 'tutorsPage');

        return view('livewire.coordinator.practice-reports.index', [
            'requests' => $this->paginateCollection($requests),
            'tutors' => $tutors,
        ])->layout('layouts.app', ['title' => 'Reportes finales']);
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

    private function isEligible(PracticeRequest $request): bool
    {
        return $request->practicePlan()->whereHas('versions', fn ($query) => $query
            ->where('status', PracticePlanStatus::Approved->value))->exists()
            && $request->acceptanceSubmissions()
                ->where('status', PracticeAcceptanceStatus::Accepted->value)
                ->exists();
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);
    }
}
