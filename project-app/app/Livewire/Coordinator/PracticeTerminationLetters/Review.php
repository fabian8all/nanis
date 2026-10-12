<?php

namespace App\Livewire\Coordinator\PracticeTerminationLetters;

use App\Enums\PracticeRequestStatus;
use App\Enums\PracticeTerminationLetterStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Models\PracticeTerminationLetter;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Review extends Component
{
    public int $practiceTerminationLetterId;

    public string $reviewComment = '';

    public bool $reviewChecklistConfirmed = false;

    public function mount(PracticeTerminationLetter $practiceTerminationLetter): void
    {
        $this->authorizeCoordinator();
        $this->practiceTerminationLetterId = $practiceTerminationLetter->id;
    }

    public function approve(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'reviewComment' => ['nullable', 'string', 'max:2000'],
            'reviewChecklistConfirmed' => ['accepted'],
        ], [
            'reviewChecklistConfirmed.accepted' => 'Confirma que verificaste todos los elementos de la carta antes de aceptarla.',
        ]);

        $this->reviewPendingLetter(
            PracticeTerminationLetterStatus::Accepted,
            $data['reviewComment'] ?: null,
        );

        session()->flash('status', 'La carta de término quedó aceptada.');
        $this->redirectRoute('coordinacion.practice-termination-letters.index');
    }

    public function returnForChanges(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'reviewComment' => ['required', 'string', 'max:2000'],
        ], [
            'reviewComment.required' => 'Escribe el motivo de devolución para que el alumno pueda corregir la carta.',
        ]);

        $this->reviewPendingLetter(
            PracticeTerminationLetterStatus::Returned,
            $data['reviewComment'],
        );

        session()->flash('status', 'La carta se devolvió al alumno para corrección.');
        $this->redirectRoute('coordinacion.practice-termination-letters.index');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
        $letter = PracticeTerminationLetter::query()
            ->with([
                'reviewer:id,name',
                'practiceRequest' => fn ($query) => $query->select(['id', 'user_id', 'company_id', 'status']),
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
            ])
            ->findOrFail($this->practiceTerminationLetterId);

        return view('livewire.coordinator.practice-termination-letters.review', [
            'letter' => $letter,
        ])->layout('layouts.app', ['title' => 'Revisar carta de término']);
    }

    private function reviewPendingLetter(
        PracticeTerminationLetterStatus $status,
        ?string $comment,
    ): void {
        DB::transaction(function () use ($status, $comment): void {
            $letter = PracticeTerminationLetter::query()
                ->findOrFail($this->practiceTerminationLetterId);
            $request = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($letter->practice_request_id);
            $letter = PracticeTerminationLetter::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceTerminationLetterId);

            if (
                $request->status !== PracticeRequestStatus::Approved
                || $letter->status !== PracticeTerminationLetterStatus::PendingReview
            ) {
                throw ValidationException::withMessages([
                    'request' => 'Esta carta ya no está pendiente de revisión.',
                ]);
            }

            $letter->update([
                'status' => $status,
                'review_comment' => $comment,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);
    }
}
