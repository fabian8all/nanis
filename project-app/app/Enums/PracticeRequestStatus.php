<?php

namespace App\Enums;

enum PracticeRequestStatus: string
{
    case Pending = 'pending';
    case Returned = 'returned';
    case Approved = 'approved';
    case Completed = 'completed';
}
