<?php

namespace App\Livewire\Coordinator\Companies;

use App\Mail\SupervisorInvitationMail;
use App\Models\Company;
use App\Models\SupervisorInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class InviteSupervisor extends Component
{
    public int $companyId;

    public string $email = '';

    public function mount(Company $company): void
    {
        $this->authorizeCoordinator();
        $this->companyId = $company->id;
    }

    public function sendInvitation(): void
    {
        $this->authorizeCoordinator();
        $data = $this->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        $company = Company::query()->findOrFail($this->companyId);
        $token = Str::random(64);
        $invitation = DB::transaction(function () use ($company, $data, $token): SupervisorInvitation {
            SupervisorInvitation::query()
                ->where('company_id', $company->id)
                ->where('email', $data['email'])
                ->whereNull('accepted_at')
                ->update(['expires_at' => now()]);

            return $company->supervisorInvitations()->create([
                'coordinator_id' => auth()->id(),
                'email' => mb_strtolower($data['email']),
                'token_hash' => hash('sha256', $token),
                'expires_at' => now()->addDays(7),
            ]);
        });

        Mail::to($invitation->email)->send(new SupervisorInvitationMail($invitation, $token));

        session()->flash('status', 'La invitación para el supervisor fue enviada.');
        $this->redirectRoute('coordinacion.companies.index');
    }

    public function render(): View
    {
        $this->authorizeCoordinator();

        return view('livewire.coordinator.companies.invite-supervisor', [
            'company' => Company::query()->findOrFail($this->companyId),
        ])->layout('layouts.app', ['title' => 'Invitar supervisor']);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->isCoordinator(), 403);
    }
}
