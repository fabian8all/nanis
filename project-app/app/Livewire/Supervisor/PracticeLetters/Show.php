<?php

namespace App\Livewire\Supervisor\PracticeLetters;

use App\Enums\PracticeAcceptanceStatus;
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
        $this->authorizeViewer();
        $this->practiceLetterId = $practiceLetter->id;
    }

    public function render(): View
    {
        $this->authorizeViewer();

        $letter = PracticeLetter::query()
            ->whereKey($this->practiceLetterId)
            ->whereHas('practiceRequest', function ($query): void {
                $user = auth()->user();
                $query->whereIn('status', [
                    PracticeRequestStatus::Approved->value,
                    PracticeRequestStatus::Completed->value,
                ]);
                if ($user->role === UserRole::Supervisor) {
                    $query
                        ->where('company_id', $user->company_id)
                        ->where(function ($scope) use ($user): void {
                            $scope
                                ->whereDoesntHave('acceptanceSubmissions', fn ($submissions) => $submissions
                                    ->where('status', PracticeAcceptanceStatus::Accepted->value))
                                ->orWhereHas('acceptanceSubmissions', fn ($submissions) => $submissions
                                    ->where('status', PracticeAcceptanceStatus::Accepted->value)
                                    ->where('supervisor_id', $user->id));
                        });
                } else {
                    $query->where('tutor_id', $user->id);
                }
            })
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
        $canUploadAcceptance = auth()->user()->role === UserRole::Supervisor
            && in_array($latestSubmission?->status, [null, PracticeAcceptanceStatus::Returned], true);

        return view('livewire.practice-letters.print-letter', [
            'letter' => $letter,
            'printDate' => now()->locale('es')->translatedFormat('j \d\e F \d\e\l Y'),
            'formattedAccountNumber' => substr($letter->practiceRequest->account_number, 0, 4)
                .'-'.substr($letter->practiceRequest->account_number, 4),
            'printButtonText' => 'Imprimir carta para consulta',
            'returnUrl' => auth()->user()->role === UserRole::Supervisor
                ? route('supervisor.practice-tracking.index')
                : route('tutor.practice-records.show', $letter->practiceRequest),
            'uploadUrl' => $canUploadAcceptance
                ? route('supervisor.practice-acceptances.upload', $letter)
                : null,
        ])->layout('layouts.app', ['title' => $letter->folio]);
    }

    private function authorizeViewer(): void
    {
        $user = auth()->user();
        abort_unless(
            $user?->isSupervisor() && $user->email_verified_at !== null
            || $user?->role === UserRole::Tutor,
            403,
        );
    }
}
