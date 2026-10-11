<?php

namespace App\Livewire\Student\PracticeLetters;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeLetter;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $practiceLetterId;

    public function mount(PracticeLetter $practiceLetter): void
    {
        $this->authorizeStudent();
        $this->practiceLetterId = $practiceLetter->id;
    }

    public function render(): View
    {
        $this->authorizeStudent();

        $letter = PracticeLetter::query()
            ->whereKey($this->practiceLetterId)
            ->whereHas('practiceRequest', fn ($query) => $query
                ->where('user_id', auth()->id())
                ->where('status', PracticeRequestStatus::Approved->value))
            ->with([
                'practiceRequest' => fn ($query) => $query->select([
                    'id',
                    'user_id',
                    'company_id',
                    'nss',
                    'account_number',
                    'grade_group',
                ]),
                'practiceRequest.student:id,name',
                'practiceRequest.company:id,name',
            ])
            ->firstOrFail();

        return view('livewire.practice-letters.print-letter', [
            'letter' => $letter,
            'printDate' => now()->locale('es')->translatedFormat('j \d\e F \d\e\l Y'),
            'formattedAccountNumber' => substr($letter->practiceRequest->account_number, 0, 4)
                .'-'.substr($letter->practiceRequest->account_number, 4),
            'printButtonText' => 'Imprimir / guardar como PDF',
            'returnUrl' => route('alumno.practice-request.show'),
        ])->layout('layouts.app', ['title' => $letter->folio]);
    }

    private function authorizeStudent(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Student, 403);
    }
}
