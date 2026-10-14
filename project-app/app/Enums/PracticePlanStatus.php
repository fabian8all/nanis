<?php

namespace App\Enums;

enum PracticePlanStatus: string
{
    case PendingSupervisorReview = 'pending_supervisor_review';
    case PendingCoordinatorReview = 'pending_coordinator_review';
    case ReturnedBySupervisor = 'returned_by_supervisor';
    case ReturnedByCoordinator = 'returned_by_coordinator';
    case Approved = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::PendingSupervisorReview => 'Pendiente de revisión por el Supervisor',
            self::PendingCoordinatorReview => 'Pendiente de revisión por Coordinación',
            self::ReturnedBySupervisor => 'Devuelta por el Supervisor',
            self::ReturnedByCoordinator => 'Devuelta por Coordinación',
            self::Approved => 'Aprobada',
        };
    }
}
