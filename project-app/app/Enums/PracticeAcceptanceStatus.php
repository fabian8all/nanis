<?php

namespace App\Enums;

enum PracticeAcceptanceStatus: string
{
    case PendingReview = 'pending_review';
    case Returned = 'returned';
    case Accepted = 'accepted';
}
