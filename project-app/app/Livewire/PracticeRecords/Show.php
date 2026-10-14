<?php

namespace App\Livewire\PracticeRecords;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use App\Models\PracticeTerminationLetterIssuance;
use App\Services\PracticeTrackingSummary;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public int $practiceRequestId;

    public function mount(PracticeRequest $practiceRequest): void
    {
        $this->practiceRequestId = $practiceRequest->id;
        $this->authorizeViewer($practiceRequest);
    }

    public function render(PracticeTrackingSummary $summary): View
    {
        $practiceRequest = PracticeRequest::query()
            ->with([
                'student:id,name',
                'company:id,name',
                'letter',
                'latestAcceptanceSubmission',
                'practicePlan.latestVersion',
                'practiceReport.latestVersion',
                'practiceReport.approvedVersion.attachments',
                'practiceEvaluation',
                'practiceSupervisorEvaluation',
                'terminationLetterIssuance',
            ])
            ->findOrFail($this->practiceRequestId);
        $this->authorizeViewer($practiceRequest);
        $user = auth()->user();
        $isSupervisor = $user?->role === UserRole::Supervisor;
        $isCoordinator = $user?->role === UserRole::Coordinator;
        $requirements = $isSupervisor ? [] : $summary->closureRequirements($practiceRequest);
        $canIssueLetter = $isCoordinator
            && $practiceRequest->status === PracticeRequestStatus::Approved
            && ! $practiceRequest->terminationLetterIssuance
            && collect($requirements)
                ->reject(fn (array $item): bool => $item['label'] === 'Carta de término emitida')
                ->every(fn (array $item): bool => $item['complete']);
        $latestAcceptance = $practiceRequest->latestAcceptanceSubmission;
        $latestPlan = $practiceRequest->practicePlan?->latestVersion;
        $latestReport = $practiceRequest->practiceReport?->latestVersion;
        $approvedReport = $practiceRequest->practiceReport?->approvedVersion;
        $documentStatuses = [
            'presentation' => $practiceRequest->letter ? 'Emitida' : 'No emitida',
            'acceptance' => $isSupervisor
                ? ($latestAcceptance?->status === PracticeAcceptanceStatus::Accepted ? 'Aprobada' : 'No disponible')
                : match ($latestAcceptance?->status) {
                    PracticeAcceptanceStatus::PendingReview => 'En revisión',
                    PracticeAcceptanceStatus::Returned => 'Devuelta',
                    PracticeAcceptanceStatus::Accepted => 'Aprobada',
                    default => 'No entregada',
                },
            'plan' => match ($latestPlan?->status) {
                PracticePlanStatus::PendingSupervisorReview,
                PracticePlanStatus::PendingCoordinatorReview => 'En revisión',
                PracticePlanStatus::ReturnedBySupervisor,
                PracticePlanStatus::ReturnedByCoordinator => 'Devuelto',
                PracticePlanStatus::Approved => 'Aprobado',
                default => 'No entregado',
            },
            'report' => $approvedReport?->status === PracticeReportStatus::Approved
                ? 'Aprobado'
                : match ($latestReport?->status) {
                    PracticeReportStatus::Draft => 'Borrador',
                    PracticeReportStatus::PendingTutorReview => 'En revisión',
                    PracticeReportStatus::Returned => 'Devuelto',
                    PracticeReportStatus::Approved => 'Aprobado',
                    default => 'No entregado',
                },
            'supervisor_report' => $approvedReport?->status === PracticeReportStatus::Approved
                ? 'Aprobado'
                : 'No disponible',
            'supervisor_evaluation' => match ($practiceRequest->practiceSupervisorEvaluation?->status) {
                PracticeEvaluationStatus::Draft => 'Borrador',
                PracticeEvaluationStatus::Submitted => 'Enviada',
                default => 'No entregada',
            },
            'tutor_evaluation' => match ($practiceRequest->practiceEvaluation?->status) {
                PracticeEvaluationStatus::Draft => 'Borrador',
                PracticeEvaluationStatus::Submitted => 'Enviada',
                default => 'No entregada',
            },
            'termination_letter' => $practiceRequest->terminationLetterIssuance ? 'Emitida' : 'No emitida',
        ];

        return view('livewire.practice-records.show', [
            'practiceRequest' => $practiceRequest,
            'latestPlan' => $latestPlan,
            'approvedReport' => $approvedReport,
            'requirements' => $requirements,
            'documentStatuses' => $documentStatuses,
            'isComplete' => ! $isSupervisor && collect($requirements)->every(fn (array $item): bool => $item['complete']),
            'isCoordinator' => $isCoordinator,
            'isSupervisor' => $isSupervisor,
            'isStudent' => $user?->role === UserRole::Student,
            'userIsTutor' => $user?->role === UserRole::Tutor,
            'canIssueLetter' => $canIssueLetter,
        ])->layout('layouts.app', ['title' => 'Expediente de práctica']);
    }

    public function issueTerminationLetter(): void
    {
        $this->authorizeCoordinator();

        $issuance = DB::transaction(function (): PracticeTerminationLetterIssuance {
            $practiceRequest = PracticeRequest::query()
                ->lockForUpdate()
                ->findOrFail($this->practiceRequestId);
            $existing = $practiceRequest->terminationLetterIssuance()->first();
            if ($existing) {
                return $existing;
            }

            $requirements = app(PracticeTrackingSummary::class)->closureRequirements($practiceRequest);
            $pending = collect($requirements)
                ->reject(fn (array $item): bool => $item['label'] === 'Carta de término emitida')
                ->filter(fn (array $item): bool => ! $item['complete']);
            if ($practiceRequest->status !== PracticeRequestStatus::Approved || $pending->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'issuance' => 'La carta solo puede emitirse después de aprobar el reporte y enviar ambas evaluaciones.',
                ]);
            }

            $issuance = $practiceRequest->terminationLetterIssuance()->create([
                'issued_by' => auth()->id(),
                'issued_at' => now(),
            ]);
            $practiceRequest->update(['status' => PracticeRequestStatus::Completed]);

            return $issuance;
        });

        $this->redirectRoute('practice-termination-letters.print', $issuance);
    }

    private function authorizeViewer(PracticeRequest $practiceRequest): void
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $canView = match ($user->role) {
            UserRole::Coordinator => true,
            UserRole::Student => $practiceRequest->user_id === $user->id,
            UserRole::Tutor => $practiceRequest->tutor_id === $user->id,
            UserRole::Supervisor => $user->isSupervisor()
                && $user->email_verified_at !== null
                && $user->company_id === $practiceRequest->company_id
                && $practiceRequest->acceptanceSubmissions()
                    ->where('supervisor_id', $user->id)
                    ->where('status', PracticeAcceptanceStatus::Accepted->value)
                    ->exists(),
            default => false,
        };

        abort_unless($canView, 404);
    }

    private function authorizeCoordinator(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Coordinator, 403);
    }
}
