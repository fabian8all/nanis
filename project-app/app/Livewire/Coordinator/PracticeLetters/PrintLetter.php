<?php

namespace App\Livewire\Coordinator\PracticeLetters;

use App\Models\PracticeLetter;
use Illuminate\View\View;
use Livewire\Component;

class PrintLetter extends Component
{
    public int $practiceLetterId;

    public function mount(PracticeLetter $practiceLetter): void
    {
        $this->authorizeCoordinator();
        $this->practiceLetterId = $practiceLetter->id;
    }

    public function render(): View
    {
        $this->authorizeCoordinator();

        $letter = PracticeLetter::query()
            ->with([
                'practiceRequest' => fn ($query) => $query->select([
                    'id',
                    'user_id',
                    'company_id',
                    'ucol_email',
                    'nss',
                    'account_number',
                    'grade_group',
                ]),
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
            ])
            ->findOrFail($this->practiceLetterId);

        return view('livewire.coordinator.practice-letters.print-letter', [
            'letter' => $letter,
            'printDate' => now()->locale('es')->translatedFormat('j \d\e F \d\e\l Y'),
            'formattedAccountNumber' => substr($letter->practiceRequest->account_number, 0, 4)
                .'-'.substr($letter->practiceRequest->account_number, 4),
        ])->layout('layouts.app', ['title' => $letter->folio]);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
