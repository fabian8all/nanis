<?php

namespace App\Enums;

enum PracticePlanStatus: string
{
    case PendingSupervisorReview = 'pending_supervisor_review';
    case PendingCoordinatorReview = 'pending_coordinator_review';
    case ReturnedBySupervisor = 'returned_by_supervisor';
    case ReturnedByCoordinator = 'returned_by_coordinator';
    case Approved = 'approved';
}
