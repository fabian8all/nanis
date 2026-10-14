<?php

namespace App\Enums;

enum PracticeReportStatus: string
{
    case Draft = 'draft';
    case PendingTutorReview = 'pending_tutor_review';
    case Returned = 'returned';
    case Approved = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Borrador',
            self::PendingTutorReview => 'Pendiente de revisión por el Tutor',
            self::Returned => 'Devuelta',
            self::Approved => 'Aprobada',
        };
    }
}
