<?php

namespace App\Livewire\Supervisor\PracticePlans;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticePlanStatus;
use App\Models\PracticePlanVersion;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Review extends Component
{
    public int $practicePlanVersionId;

    public string $reviewComment = '';

    public function mount(PracticePlanVersion $practicePlanVersion): void
    {
        $this->practicePlanVersionId = $practicePlanVersion->id;
        $this->authorizeAssignedSupervisor();
    }

    public function approve(): void
    {
        $this->authorizeAssignedSupervisor();
        DB::transaction(function (): void {
            $version = $this->lockPendingVersion();
            $version->update([
                'status' => PracticePlanStatus::PendingCoordinatorReview,
                'supervisor_reviewed_by' => auth()->id(),
                'supervisor_reviewed_at' => now(),
                'supervisor_review_comment' => null,
            ]);
        });

        session()->flash('status', 'El plan quedó aprobado por el supervisor y pasó a Coordinación.');
        $this->redirectRoute('supervisor.practice-plans.index');
    }

    public function returnForChanges(): void
    {
        $this->authorizeAssignedSupervisor();
        $data = $this->validate([
            'reviewComment' => ['required', 'string', 'max:2000'],
        ], [
            'reviewComment.required' => 'Escribe el motivo de la devolución para orientar al alumno.',
        ]);

        DB::transaction(function () use ($data): void {
            $version = $this->lockPendingVersion();
            $version->update([
                'status' => PracticePlanStatus::ReturnedBySupervisor,
                'supervisor_reviewed_by' => auth()->id(),
                'supervisor_reviewed_at' => now(),
                'supervisor_review_comment' => $data['reviewComment'],
            ]);
        });

        session()->flash('status', 'El plan se devolvió al alumno para corrección.');
        $this->redirectRoute('supervisor.practice-plans.index');
    }

    public function render(): View
    {
        $this->authorizeAssignedSupervisor();
        $version = PracticePlanVersion::query()
            ->with([
                'activities',
                'supervisorReviewer:id,name',
                'coordinatorReviewer:id,name',
                'practicePlan.practiceRequest.student:id,name',
                'practicePlan.practiceRequest.company:id,name',
                'practicePlan.versions.activities',
                'practicePlan.versions.supervisorReviewer:id,name',
                'practicePlan.versions.coordinatorReviewer:id,name',
            ])
            ->findOrFail($this->practicePlanVersionId);

        return view('livewire.supervisor.practice-plans.review', [
            'version' => $version,
            'history' => $version->practicePlan->versions->sortBy('version'),
        ])->layout('layouts.app', ['title' => 'Revisar plan de trabajo']);
    }

    private function lockPendingVersion(): PracticePlanVersion
    {
        $version = PracticePlanVersion::query()
            ->findOrFail($this->practicePlanVersionId);
        PracticeRequest::query()
            ->lockForUpdate()
            ->findOrFail($version->practicePlan->practice_request_id);
        $version = PracticePlanVersion::query()
            ->lockForUpdate()
            ->findOrFail($this->practicePlanVersionId);

        if ($version->status !== PracticePlanStatus::PendingSupervisorReview) {
            throw ValidationException::withMessages([
                'reviewComment' => 'Esta versión ya no está pendiente de revisión por el supervisor.',
            ]);
        }

        return $version;
    }

    private function authorizeAssignedSupervisor(): void
    {
        $user = auth()->user();
        abort_unless($user?->isSupervisor() && $user->email_verified_at !== null, 403);

        $isAssigned = PracticePlanVersion::query()
            ->whereKey($this->practicePlanVersionId)
            ->whereHas('practicePlan.practiceRequest.acceptanceSubmissions', fn ($query) => $query
                ->where('supervisor_id', $user->id)
                ->where('status', PracticeAcceptanceStatus::Accepted->value))
            ->exists();
        abort_unless($isAssigned, 404);
    }
}
