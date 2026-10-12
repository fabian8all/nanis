<?php

namespace App\Enums;

enum PracticeTerminationLetterStatus: string
{
    case PendingReview = 'pending_review';
    case Returned = 'returned';
    case Accepted = 'accepted';
}
