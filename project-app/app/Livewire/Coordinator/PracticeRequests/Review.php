<?php

namespace App\Livewire\Coordinator\PracticeRequests;

use App\Enums\PracticeRequestStatus;
use App\Models\PracticeLetter;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Review extends Component
{
    public int $practiceRequestId;

    public string $letterRecipient = '';

    public string $position = '';

    public string $practiceLocation = '';

    public string $startDate = '';

    public string $endDate = '';

    public string $reviewComment = '';

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->authorizeCoordinator();
        abort_unless($practiceRequest->status === PracticeRequestStatus::Pending, 404);

        $this->practiceRequestId = $practiceRequest->id;
        $this->letterRecipient = $practiceRequest->letter_recipient;
        $this->position = $practiceRequest->position;
        $this->practiceLocation = $practiceRequest->practice_location;
        $this->startDate = $practiceRequest->start_date->toDateString();
        $this->endDate = $practiceRequest->end_date->toDateString();
    }

    public function returnForChanges(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'reviewComment' => ['required', 'string', 'max:2000'],
        ], [
            'reviewComment.required' => 'Escribe el motivo de la devolución para que el alumno pueda corregir la solicitud.',
        ]);

        DB::transaction(function () use ($data): void {
            $request = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceRequestId);
            $this->assertPending($request);

            $request->reviews()->create([
                'coordinator_id' => auth()->id(),
                'decision' => PracticeRequestStatus::Returned,
                'comment' => $data['reviewComment'],
                'reviewed_at' => now(),
            ]);
            $request->update(['status' => PracticeRequestStatus::Returned]);
        });

        session()->flash('status', 'La solicitud se devolvió al alumno para correcciones.');
        $this->redirectRoute('coordinacion.practice-requests.index');
    }

    public function approve(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'letterRecipient' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'practiceLocation' => ['required', 'string', 'max:255'],
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after:startDate'],
            'reviewComment' => ['nullable', 'string', 'max:2000'],
        ], [
            'endDate.after' => 'La fecha de término debe ser posterior a la fecha de inicio.',
        ]);

        $letter = DB::transaction(function () use ($data): PracticeLetter {
            $request = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceRequestId);
            $this->assertPending($request);
            if (
                strlen($request->nss ?? '') !== 11
                || ! ctype_digit($request->nss ?? '')
                || strlen($request->account_number ?? '') !== 8
                || ! ctype_digit($request->account_number ?? '')
            ) {
                throw ValidationException::withMessages([
                    'request' => 'La solicitud no contiene un NSS y número de cuenta válidos; devuélvela para corrección antes de aprobar.',
                ]);
            }

            $year = (int) now()->format('Y');
            $now = now();
            DB::table('practice_letter_sequences')->insertOrIgnore([
                'year' => $year,
                'last_number' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $sequence = DB::table('practice_letter_sequences')
                ->where('year', $year)
                ->lockForUpdate()
                ->first();
            $number = $sequence->last_number + 1;

            DB::table('practice_letter_sequences')
                ->where('year', $year)
                ->update(['last_number' => $number, 'updated_at' => $now]);

            $letter = $request->letter()->create([
                'coordinator_id' => auth()->id(),
                'folio_year' => $year,
                'folio_number' => $number,
                'folio' => sprintf('CP-%d-%04d', $year, $number),
                'letter_recipient' => $data['letterRecipient'],
                'position' => $data['position'],
                'practice_location' => $data['practiceLocation'],
                'start_date' => $data['startDate'],
                'end_date' => $data['endDate'],
                'issued_at' => $now,
            ]);

            $request->reviews()->create([
                'coordinator_id' => auth()->id(),
                'decision' => PracticeRequestStatus::Approved,
                'comment' => $data['reviewComment'] ?: null,
                'reviewed_at' => $now,
            ]);
            $request->update(['status' => PracticeRequestStatus::Approved]);

            return $letter;
        });

        $this->redirectRoute('coordinacion.practice-letters.print', ['practiceLetter' => $letter]);
    }

    public function render(): View
    {
        $this->authorizeCoordinator();
        $request = PracticeRequest::query()
            ->select(['id', 'user_id', 'company_id', 'ucol_email', 'grade_group', 'start_date', 'end_date', 'practice_location', 'letter_recipient', 'position', 'status', 'created_at'])
            ->with([
                'student:id,name,email',
                'company:id,name',
                'reviews' => fn ($query) => $query->with('coordinator:id,name')->oldest('reviewed_at'),
            ])
            ->findOrFail($this->practiceRequestId);

        return view('livewire.coordinator.practice-requests.review', [
            'practiceRequest' => $request,
        ])->layout('layouts.app', ['title' => 'Revisar solicitud']);
    }

    private function assertPending(PracticeRequest $request): void
    {
        if ($request->status !== PracticeRequestStatus::Pending) {
            throw ValidationException::withMessages([
                'request' => 'Esta solicitud ya no está pendiente de revisión.',
            ]);
        }
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
