<?php

namespace App\Livewire\Supervisor\PracticeLetters;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Models\PracticeLetter;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $practiceLetterId;

    public function mount(PracticeLetter $practiceLetter): void
    {
        $this->authorizeSupervisor();
        $this->practiceLetterId = $practiceLetter->id;
    }

    public function render(): View
    {
        $this->authorizeSupervisor();

        $letter = PracticeLetter::query()
            ->whereKey($this->practiceLetterId)
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
                    'nss',
                    'account_number',
                    'grade_group',
                ]),
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
                'practiceRequest.latestAcceptanceSubmission',
            ])
            ->firstOrFail();

        $latestSubmission = $letter->practiceRequest->latestAcceptanceSubmission;

        return view('livewire.practice-letters.print-letter', [
            'letter' => $letter,
            'printDate' => now()->locale('es')->translatedFormat('j \d\e F \d\e\l Y'),
            'formattedAccountNumber' => substr($letter->practiceRequest->account_number, 0, 4)
                .'-'.substr($letter->practiceRequest->account_number, 4),
            'printButtonText' => 'Imprimir carta para consulta',
            'returnUrl' => route('supervisor.practice-letters.index'),
            'uploadUrl' => $latestSubmission?->status === PracticeAcceptanceStatus::PendingReview
                ? null
                : route('supervisor.practice-acceptances.upload', $letter),
        ])->layout('layouts.app', ['title' => $letter->folio]);
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(auth()->user()?->isSupervisor() && auth()->user()?->email_verified_at !== null, 403);
    }
}
