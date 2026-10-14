<?php

namespace App\Enums;

enum PracticeAcceptanceStatus: string
{
    case PendingReview = 'pending_review';
    case Returned = 'returned';
    case Accepted = 'accepted';

    public function label(): string
    {
        return match ($this) {
            self::PendingReview => 'Pendiente de revisión',
            self::Returned => 'Devuelta',
            self::Accepted => 'Aprobada',
        };
    }
}
