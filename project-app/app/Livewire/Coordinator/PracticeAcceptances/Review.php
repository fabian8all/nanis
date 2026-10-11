<?php

namespace App\Livewire\Coordinator\PracticeAcceptances;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Models\PracticeAcceptanceSubmission;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Review extends Component
{
    public int $practiceAcceptanceId;

    public string $reviewComment = '';

    public function mount(PracticeAcceptanceSubmission $practiceAcceptance): void
    {
        $this->authorizeCoordinator();
        $this->practiceAcceptanceId = $practiceAcceptance->id;
    }

    public function approve(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'reviewComment' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data): void {
            $submission = PracticeAcceptanceSubmission::query()
                ->findOrFail($this->practiceAcceptanceId);
            $request = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($submission->practice_request_id);
            $submission = PracticeAcceptanceSubmission::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceAcceptanceId);
            $this->assertPendingReview($submission, $request);

            $alreadyAccepted = $request->acceptanceSubmissions()
                ->where('status', PracticeAcceptanceStatus::Accepted->value)
                ->exists();
            if ($alreadyAccepted) {
                throw ValidationException::withMessages([
                    'request' => 'Esta práctica ya tiene una carta de aceptación aprobada.',
                ]);
            }

            $submission->update([
                'status' => PracticeAcceptanceStatus::Accepted,
                'review_comment' => $data['reviewComment'] ?: null,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        session()->flash('status', 'La carta de aceptación quedó aprobada.');
        $this->redirectRoute('coordinacion.practice-acceptances.index');
    }

    public function returnForChanges(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'reviewComment' => ['required', 'string', 'max:2000'],
        ], [
            'reviewComment.required' => 'Escribe el motivo de la devolución para que el supervisor pueda corregir el archivo.',
        ]);

        DB::transaction(function () use ($data): void {
            $submission = PracticeAcceptanceSubmission::query()
                ->findOrFail($this->practiceAcceptanceId);
            $request = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($submission->practice_request_id);
            $submission = PracticeAcceptanceSubmission::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceAcceptanceId);
            $this->assertPendingReview($submission, $request);

            $submission->update([
                'status' => PracticeAcceptanceStatus::Returned,
                'review_comment' => $data['reviewComment'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        session()->flash('status', 'La carta se devolvió al supervisor para corrección.');
        $this->redirectRoute('coordinacion.practice-acceptances.index');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
        $submission = PracticeAcceptanceSubmission::query()
            ->with([
                'supervisor:id,name,email',
                'reviewer:id,name',
                'practiceRequest' => fn ($query) => $query->select([
                    'id',
                    'user_id',
                    'company_id',
                    'grade_group',
                    'status',
                ]),
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'practiceRequest.letter:id,practice_request_id,folio',
            ])
            ->findOrFail($this->practiceAcceptanceId);

        return view('livewire.coordinator.practice-acceptances.review', [
            'submission' => $submission,
            'history' => PracticeAcceptanceSubmission::query()
                ->where('practice_request_id', $submission->practice_request_id)
                ->with('reviewer:id,name')
                ->orderBy('version')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Revisar carta de aceptación']);
    }

    private function assertPendingReview(
        PracticeAcceptanceSubmission $submission,
        PracticeRequest $request,
    ): void {
        if (
            $request->status !== PracticeRequestStatus::Approved
            || $submission->status !== PracticeAcceptanceStatus::PendingReview
        ) {
            throw ValidationException::withMessages([
                'request' => 'Esta carta ya no está pendiente de revisión.',
            ]);
        }
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
