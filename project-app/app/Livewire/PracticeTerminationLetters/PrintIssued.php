<?php

namespace App\Livewire\PracticeTerminationLetters;

use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Models\PracticeTerminationLetterIssuance;
use Illuminate\View\View;
use Livewire\Component;

class PrintIssued extends Component
{
    public int $issuanceId;

    public function mount(PracticeTerminationLetterIssuance $issuance): void
    {
        $this->issuanceId = $issuance->id;
        $this->authorizeViewer($issuance->practiceRequest);
    }

    public function render(): View
    {
        $issuance = PracticeTerminationLetterIssuance::query()
            ->with([
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'practiceRequest.tutor:id,name',
            ])
            ->findOrFail($this->issuanceId);
        $this->authorizeViewer($issuance->practiceRequest);

        return view('livewire.practice-termination-letters.print-issued', [
            'issuance' => $issuance,
            'practiceRequest' => $issuance->practiceRequest,
            'printDate' => $issuance->issued_at->locale('es')->translatedFormat('j \d\e F \d\e\l Y'),
            'returnUrl' => match (auth()->user()->role) {
                UserRole::Coordinator => route('coordinacion.practice-records.show', $issuance->practiceRequest),
                UserRole::Student => route('alumno.practice-records.show', $issuance->practiceRequest),
                UserRole::Tutor => route('tutor.practice-records.show', $issuance->practiceRequest),
                default => route('home'),
            },
        ])->layout('layouts.app', ['title' => 'Carta de término']);
    }

    private function authorizeViewer(PracticeRequest $practiceRequest): void
    {
        $user = auth()->user();
        abort_unless($user, 403);
        $allowed = match ($user->role) {
            UserRole::Coordinator => true,
            UserRole::Student => $practiceRequest->user_id === $user->id,
            UserRole::Tutor => $practiceRequest->tutor_id === $user->id,
            default => false,
        };
        abort_unless($allowed, 404);
    }
}
