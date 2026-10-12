<?php

namespace App\Enums;

enum PracticeReportStatus: string
{
    case Draft = 'draft';
    case PendingTutorReview = 'pending_tutor_review';
    case Returned = 'returned';
    case Approved = 'approved';
}
