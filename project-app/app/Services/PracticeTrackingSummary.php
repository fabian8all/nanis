<?php

namespace App\Services;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeEvaluationStatus;
use App\Enums\PracticePlanStatus;
use App\Enums\PracticeReportStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\PracticeTerminationLetterStatus;
use App\Enums\UserRole;
use App\Models\PracticeRequest;
use Illuminate\Support\Facades\Route;

class PracticeTrackingSummary
{
    /**
     * @return array<int, array{label: string, status: string, detail: string, responsible: string|null, route: string|null}>
     */
    public function stages(PracticeRequest $request): array
    {
        $latestAcceptance = $request->latestAcceptanceSubmission;
        $latestPlan = $request->practicePlan?->latestVersion;
        $latestReport = $request->practiceReport?->latestVersion;
        $supervisorEvaluation = $request->practiceSupervisorEvaluation;
        $tutorEvaluation = $request->practiceEvaluation;
        $terminationLetter = $request->terminationLetter;

        return [
            $this->requestStage($request),
            $this->acceptanceStage($request, $latestAcceptance),
            $this->planStage($request, $latestAcceptance, $latestPlan),
            $this->evaluationStage(
                'Evaluación de Supervisor',
                $request,
                $latestPlan?->status === PracticePlanStatus::Approved,
                $supervisorEvaluation?->status,
                $request->acceptanceSubmissions->first(fn ($submission): bool => $submission->status === PracticeAcceptanceStatus::Accepted)?->supervisor_id,
                UserRole::Supervisor,
            ),
            $this->reportStage($request, $latestPlan, $latestReport),
            $this->evaluationStage(
                'Evaluación de Tutor',
                $request,
                $latestReport?->status === PracticeReportStatus::Approved,
                $tutorEvaluation?->status,
                $request->tutor_id,
                UserRole::Tutor,
            ),
            $this->terminationStage($request, $terminationLetter),
            $this->closureStage($request),
        ];
    }

    /**
     * @return array<int, array{label: string, complete: bool, status: string}>
     */
    public function closureRequirements(PracticeRequest $request): array
    {
        $terminationLetter = $request->terminationLetter;
        $approvedReport = $request->practiceReport?->approvedVersion;
        $tutorEvaluation = $request->practiceEvaluation;
        $supervisorEvaluation = $request->practiceSupervisorEvaluation;

        return [
            [
                'label' => 'Carta de término aceptada',
                'complete' => $terminationLetter?->status === PracticeTerminationLetterStatus::Accepted,
                'status' => match ($terminationLetter?->status) {
                    PracticeTerminationLetterStatus::Accepted => 'Aceptada',
                    PracticeTerminationLetterStatus::Returned => 'Devuelta',
                    PracticeTerminationLetterStatus::PendingReview => 'En revisión',
                    default => 'Pendiente',
                },
            ],
            [
                'label' => 'Reporte final aprobado',
                'complete' => $approvedReport?->status === PracticeReportStatus::Approved,
                'status' => $approvedReport?->status === PracticeReportStatus::Approved ? 'Aprobado' : 'Pendiente',
            ],
            [
                'label' => 'Evaluación terminal del Tutor enviada',
                'complete' => $tutorEvaluation?->status === PracticeEvaluationStatus::Submitted,
                'status' => match ($tutorEvaluation?->status) {
                    PracticeEvaluationStatus::Submitted => 'Enviada',
                    PracticeEvaluationStatus::Draft => 'Borrador',
                    default => 'Pendiente',
                },
            ],
            [
                'label' => 'Evaluación terminal del Supervisor enviada',
                'complete' => $supervisorEvaluation?->status === PracticeEvaluationStatus::Submitted,
                'status' => match ($supervisorEvaluation?->status) {
                    PracticeEvaluationStatus::Submitted => 'Enviada',
                    PracticeEvaluationStatus::Draft => 'Borrador',
                    default => 'Pendiente',
                },
            ],
        ];
    }

    private function requestStage(PracticeRequest $request): array
    {
        return match ($request->status) {
            PracticeRequestStatus::Pending => $this->stage('Solicitud', 'Pendiente', 'Coordinación debe revisar la solicitud.', UserRole::Coordinator, 'coordinacion.practice-requests.review', [$request]),
            PracticeRequestStatus::Returned => $this->stage('Solicitud', 'Devuelta', 'El alumno debe corregir y reenviar la solicitud.', UserRole::Student, 'alumno.practice-request.show'),
            PracticeRequestStatus::Approved, PracticeRequestStatus::Completed => $this->stage('Solicitud', 'Aprobada', 'Solicitud aprobada.', null, 'alumno.practice-request.show'),
        };
    }

    private function acceptanceStage(PracticeRequest $request, mixed $submission): array
    {
        if ($submission?->status === PracticeAcceptanceStatus::Accepted) {
            return $this->stage('Aceptación', 'Aceptada', 'Aceptación empresarial aprobada.', null, 'alumno.practice-request.show');
        }

        if ($submission?->status === PracticeAcceptanceStatus::PendingReview) {
            return $this->stage('Aceptación', 'Pendiente', 'Coordinación debe revisar la carta de aceptación.', UserRole::Coordinator, 'coordinacion.practice-acceptances.review', [$submission]);
        }

        if ($submission?->status === PracticeAcceptanceStatus::Returned) {
            return $this->stage('Aceptación', 'Devuelta', 'El supervisor debe corregir y reenviar la carta.', UserRole::Supervisor, 'supervisor.practice-letters.index');
        }

        return $request->status === PracticeRequestStatus::Approved
            ? $this->stage('Aceptación', 'Pendiente', 'El supervisor debe cargar la carta de aceptación.', UserRole::Supervisor, 'supervisor.practice-letters.index')
            : $this->stage('Aceptación', 'Bloqueada', 'Pendiente de aprobación de la solicitud.', null, null);
    }

    private function planStage(PracticeRequest $request, mixed $acceptance, mixed $plan): array
    {
        if ($acceptance?->status !== PracticeAcceptanceStatus::Accepted) {
            return $this->stage('Plan', 'Bloqueado', 'Pendiente de aceptación empresarial.', null, null);
        }

        if (! $plan) {
            return $this->stage('Plan', 'Pendiente', 'El alumno debe registrar el plan de trabajo.', UserRole::Student, 'alumno.practice-plans.show', [$request]);
        }

        return match ($plan->status) {
            PracticePlanStatus::PendingSupervisorReview => $this->stage('Plan', 'Pendiente', 'El supervisor debe revisar el plan.', UserRole::Supervisor, 'supervisor.practice-plans.index'),
            PracticePlanStatus::PendingCoordinatorReview => $this->stage('Plan', 'Pendiente', 'Coordinación debe revisar el plan.', UserRole::Coordinator, 'coordinacion.practice-plans.review', [$plan]),
            PracticePlanStatus::ReturnedBySupervisor, PracticePlanStatus::ReturnedByCoordinator => $this->stage('Plan', 'Devuelto', 'El alumno debe corregir el plan.', UserRole::Student, 'alumno.practice-plans.show', [$request]),
            PracticePlanStatus::Approved => $this->stage('Plan', 'Aprobado', 'Plan aprobado.', null, 'alumno.practice-plans.show', [$request]),
        };
    }

    private function reportStage(PracticeRequest $request, mixed $plan, mixed $report): array
    {
        if ($plan?->status !== PracticePlanStatus::Approved) {
            return $this->stage('Reporte', 'Bloqueado', 'Pendiente de aprobación del plan.', null, null);
        }

        if (! $report) {
            return $this->stage('Reporte', 'Pendiente', 'El alumno debe preparar y enviar el reporte final.', UserRole::Student, 'alumno.practice-reports.show', [$request]);
        }

        return match ($report->status) {
            PracticeReportStatus::Draft, PracticeReportStatus::Returned => $this->stage('Reporte', $report->status === PracticeReportStatus::Returned ? 'Devuelto' : 'Borrador', 'El alumno debe completar o corregir el reporte.', UserRole::Student, 'alumno.practice-reports.show', [$request]),
            PracticeReportStatus::PendingTutorReview => $this->stage('Reporte', 'Pendiente', 'El tutor asignado debe revisar el reporte.', UserRole::Tutor, 'tutor.practice-reports.review', [$report]),
            PracticeReportStatus::Approved => $this->stage('Reporte', 'Aprobado', 'Reporte final aprobado.', null, 'alumno.practice-reports.show', [$request]),
        };
    }

    private function evaluationStage(
        string $label,
        PracticeRequest $request,
        bool $enabled,
        ?PracticeEvaluationStatus $status,
        ?int $responsibleId,
        UserRole $role,
    ): array {
        $actorName = $role === UserRole::Tutor ? 'El tutor asignado' : 'El supervisor asignado';
        if (! $enabled) {
            return $this->stage($label, 'Bloqueada', 'Pendiente de los requisitos previos.', null, null);
        }

        if ($status === PracticeEvaluationStatus::Submitted) {
            return $this->stage($label, 'Completada', 'Evaluación enviada.', null, null);
        }

        $detail = $status === PracticeEvaluationStatus::Draft
            ? "{$actorName} debe completar y enviar la evaluación."
            : "{$actorName} debe iniciar la evaluación terminal.";

        if ($role === UserRole::Tutor) {
            $route = 'tutor.practice-evaluations.index';
            $parameters = [];
        } else {
            $route = 'supervisor.practice-supervisor-evaluations.index';
            $parameters = [];
        }

        return $this->stage($label, 'Pendiente', $detail, $role, $route, $parameters, $responsibleId);
    }

    private function terminationStage(PracticeRequest $request, mixed $letter): array
    {
        if ($request->status !== PracticeRequestStatus::Approved && $request->status !== PracticeRequestStatus::Completed) {
            return $this->stage('Carta de término', 'Bloqueada', 'Pendiente de aprobación de la solicitud.', null, null);
        }

        if (! $letter) {
            return $this->stage('Carta de término', 'Pendiente', 'El alumno debe cargar la carta empresarial.', UserRole::Student, 'alumno.practice-termination-letters.upload', [$request]);
        }

        return match ($letter->status) {
            PracticeTerminationLetterStatus::PendingReview => $this->stage('Carta de término', 'Pendiente', 'Coordinación debe revisar la carta.', UserRole::Coordinator, 'coordinacion.practice-termination-letters.review', [$letter]),
            PracticeTerminationLetterStatus::Returned => $this->stage('Carta de término', 'Devuelta', 'El alumno debe corregir y reenviar la carta.', UserRole::Student, 'alumno.practice-termination-letters.upload', [$request]),
            PracticeTerminationLetterStatus::Accepted => $this->stage('Carta de término', 'Aceptada', 'Carta de término aceptada.', null, 'alumno.practice-termination-letters.upload', [$request]),
        };
    }

    private function closureStage(PracticeRequest $request): array
    {
        $requirements = $this->closureRequirements($request);
        $pending = collect($requirements)->reject(fn (array $item): bool => $item['complete']);
        $complete = $pending->isEmpty();

        return $this->stage(
            'Cierre',
            $complete ? 'Completo' : 'Pendiente',
            $complete
                ? 'Los documentos y evaluaciones terminales requeridos están completos.'
                : 'Pendiente: '.$pending->pluck('label')->implode(', ').'.',
            null,
            null,
        );
    }

    private function stage(
        string $label,
        string $status,
        string $detail,
        ?UserRole $responsible,
        ?string $route,
        array $parameters = [],
        ?int $responsibleId = null,
    ): array {
        return [
            'label' => $label,
            'status' => $status,
            'detail' => $detail,
            'responsible' => $responsible?->value,
            'responsible_id' => $responsibleId,
            'route' => $route && Route::has($route)
                ? route($route, $parameters)
                : null,
        ];
    }
}
