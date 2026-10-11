<?php

namespace App\Livewire\Coordinator\PracticePlans;

use App\Enums\PracticePlanStatus;
use App\Models\PracticePlanVersion;
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
        $this->authorizeCoordinator();
        $this->practicePlanVersionId = $practicePlanVersion->id;
    }

    public function approve(): void
    {
        $this->authorizeCoordinator();
        DB::transaction(function (): void {
            $version = $this->lockPendingVersion();
            $version->update([
                'status' => PracticePlanStatus::Approved,
                'coordinator_reviewed_by' => auth()->id(),
                'coordinator_reviewed_at' => now(),
                'coordinator_review_comment' => null,
            ]);
        });

        session()->flash('status', 'El plan quedó aprobado por Coordinación.');
        $this->redirectRoute('coordinacion.practice-plans.index');
    }

    public function returnForChanges(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'reviewComment' => ['required', 'string', 'max:2000'],
        ], [
            'reviewComment.required' => 'Escribe el motivo de la devolución para orientar al alumno.',
        ]);

        DB::transaction(function () use ($data): void {
            $version = $this->lockPendingVersion();
            $version->update([
                'status' => PracticePlanStatus::ReturnedByCoordinator,
                'coordinator_reviewed_by' => auth()->id(),
                'coordinator_reviewed_at' => now(),
                'coordinator_review_comment' => $data['reviewComment'],
            ]);
        });

        session()->flash('status', 'El plan se devolvió al alumno para corrección.');
        $this->redirectRoute('coordinacion.practice-plans.index');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
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

        return view('livewire.coordinator.practice-plans.review', [
            'version' => $version,
            'history' => $version->practicePlan->versions->sortBy('version'),
        ])->layout('layouts.app', ['title' => 'Revisar plan de trabajo']);
    }

    private function lockPendingVersion(): PracticePlanVersion
    {
        $version = PracticePlanVersion::query()
            ->lockForUpdate()
            ->findOrFail($this->practicePlanVersionId);

        if ($version->status !== PracticePlanStatus::PendingCoordinatorReview) {
            throw ValidationException::withMessages([
                'reviewComment' => 'Esta versión ya no está pendiente de revisión por Coordinación.',
            ]);
        }

        return $version;
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
