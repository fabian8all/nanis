<?php

namespace App\Enums;

enum PracticeSurveyRequestStatus: string
{
    case Requested = 'requested';
    case Responded = 'responded';
    case Cancelled = 'cancelled';
}
