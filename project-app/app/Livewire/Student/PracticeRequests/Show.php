<?php

namespace App\Livewire\Student\PracticeRequests;

use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public string $ucolEmail = '';

    public string $nss = '';

    public string $accountNumber = '';

    public string $gradeGroup = '';

    public string $startDate = '';

    public string $practiceLocation = '';

    public string $letterRecipient = '';

    public string $position = '';

    public string $endDate = '';

    public bool $acceptNonResponsiva = false;

    public function mount(): void
    {
        $this->authorizeStudent();
        $this->ucolEmail = auth()->user()->email;

        $request = $this->currentRequest();
        if ($request?->status === PracticeRequestStatus::Returned) {
            $this->ucolEmail = $request->ucol_email;
            $this->nss = $request->nss ?? '';
            $this->accountNumber = $request->account_number ?? '';
            $this->gradeGroup = $request->grade_group;
            $this->startDate = $request->start_date->toDateString();
            $this->practiceLocation = $request->practice_location;
            $this->letterRecipient = $request->letter_recipient;
            $this->position = $request->position;
            $this->endDate = $request->end_date->toDateString();
            $this->acceptNonResponsiva = true;
        }
    }

    public function submit(): void
    {
        $this->authorizeStudent();

        $data = $this->validate([
            'ucolEmail' => ['required', 'email', 'max:255'],
            'nss' => ['required', 'digits:11'],
            'accountNumber' => ['required', 'digits:8'],
            'gradeGroup' => ['required', 'string', 'max:255'],
            'startDate' => ['required', 'date'],
            'practiceLocation' => ['required', 'string', 'max:255'],
            'letterRecipient' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'endDate' => ['required', 'date', 'after:startDate'],
            'acceptNonResponsiva' => ['accepted'],
        ], [
            'endDate.after' => 'La fecha de término debe ser posterior a la fecha de inicio.',
            'acceptNonResponsiva.accepted' => 'Debes aceptar la carta de no responsiva para enviar la solicitud.',
        ]);

        $existingRequest = $this->currentRequest();
        $companyId = $existingRequest?->status === PracticeRequestStatus::Returned
            ? $existingRequest->company_id
            : session()->get('practice.selected_company_id');
        DB::transaction(function () use ($data, $companyId): void {
            $student = auth()->user()->newQuery()->lockForUpdate()->findOrFail(auth()->id());
            $request = $student->practiceRequests()
                ->whereIn('status', [
                    PracticeRequestStatus::Pending,
                    PracticeRequestStatus::Returned,
                    PracticeRequestStatus::Approved,
                    PracticeRequestStatus::Completed,
                ])
                ->lockForUpdate()
                ->first();

            if ($request && $request->status !== PracticeRequestStatus::Returned) {
                throw ValidationException::withMessages([
                    'request' => 'Ya tienes una solicitud en trámite. No puedes enviar otra.',
                ]);
            }

            $effectiveCompanyId = $request?->company_id ?? $companyId;
            $lockedCompany = Company::query()
                ->when(
                    ! $request || $request->status !== PracticeRequestStatus::Returned,
                    fn ($query) => $query->where('is_active', true),
                )
                ->lockForUpdate()
                ->find($effectiveCompanyId);

            if (! $lockedCompany) {
                session()->forget('practice.selected_company_id');
                throw ValidationException::withMessages([
                    'company' => 'Selecciona una empresa con convenio vigente antes de enviar la solicitud.',
                ]);
            }

            $attributes = [
                'company_id' => $lockedCompany->id,
                'ucol_email' => $data['ucolEmail'],
                'nss' => $data['nss'],
                'account_number' => $data['accountNumber'],
                'grade_group' => $data['gradeGroup'],
                'start_date' => $data['startDate'],
                'practice_location' => $data['practiceLocation'],
                'letter_recipient' => $data['letterRecipient'],
                'position' => $data['position'],
                'end_date' => $data['endDate'],
                'status' => PracticeRequestStatus::Pending,
                'non_responsiva_accepted_at' => now(),
            ];

            if ($request) {
                $request->update($attributes);
            } else {
                $student->practiceRequests()->create($attributes);
            }
        });

        session()->forget('practice.selected_company_id');
        session()->flash('status', 'Tu solicitud se envió correctamente.');
        $this->redirectRoute('alumno.practice-request.show');
    }

    public function render(): View
    {
        $this->authorizeStudent();

        $request = $this->currentRequest();

        $companyId = $request?->status === PracticeRequestStatus::Returned
            ? $request->company_id
            : session()->get('practice.selected_company_id');
        $companyMustBeActive = $request?->status !== PracticeRequestStatus::Returned;
        $company = is_int($companyId)
            ? Company::query()
                ->when($companyMustBeActive, fn ($query) => $query->where('is_active', true))
                ->find($companyId)
            : null;

        if ($companyId !== null && ! $company) {
            session()->forget('practice.selected_company_id');
        }

        return view('livewire.student.practice-requests.show', [
            'practiceRequest' => $request,
            'latestReview' => $request?->reviews()->latest('reviewed_at')->first(),
            'selectedCompany' => $company,
            'printDate' => now()->locale('es')->translatedFormat('j \d\e F \d\e\l Y'),
        ])->layout('layouts.app', ['title' => 'Solicitud de práctica']);
    }

    private function currentRequest(): ?PracticeRequest
    {
        return PracticeRequest::query()
            ->with([
                'company',
                'letter',
                'latestAcceptanceSubmission',
                'practicePlan.latestVersion',
                'practiceReport.approvedVersion',
                'terminationLetter',
            ])
            ->where('user_id', auth()->id())
            ->whereIn('status', [
                PracticeRequestStatus::Pending,
                PracticeRequestStatus::Returned,
                PracticeRequestStatus::Approved,
                PracticeRequestStatus::Completed,
            ])
            ->latest('id')
            ->first();
    }

    private function authorizeStudent(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Student, 403);
    }
}
