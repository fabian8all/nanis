<?php

namespace App\Livewire\Supervisor\PracticeLetters;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Models\PracticeLetter;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $this->authorizeSupervisor();

        $letters = PracticeLetter::query()
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
                    'status',
                ]),
                'practiceRequest.student:id,name',
                'practiceRequest.latestAcceptanceSubmission',
                'practiceRequest.acceptanceSubmissions' => fn ($query) => $query->with('reviewer:id,name')->orderBy('version'),
            ])
            ->orderByDesc('issued_at')
            ->get();

        return view('livewire.supervisor.practice-letters.index', [
            'letters' => $letters,
        ])->layout('layouts.app', ['title' => 'Cartas pendientes de aceptación']);
    }

    private function authorizeSupervisor(): void
    {
        abort_unless(auth()->user()?->isSupervisor() && auth()->user()?->email_verified_at !== null, 403);
    }
}
