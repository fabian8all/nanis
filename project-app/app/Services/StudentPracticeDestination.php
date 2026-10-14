<?php

namespace App\Services;

use App\Enums\PracticeRequestStatus;
use App\Models\User;

final class StudentPracticeDestination
{
    public function routeFor(User $student): string
    {
        $status = $student->practiceRequests()->latest('id')->value('status');

        if ($status === null) {
            return 'alumno.companies.index';
        }

        if (! $status instanceof PracticeRequestStatus) {
            $status = PracticeRequestStatus::from($status);
        }

        return match ($status) {
            PracticeRequestStatus::Returned => 'alumno.practice-request.show',
            PracticeRequestStatus::Pending,
            PracticeRequestStatus::Approved,
            PracticeRequestStatus::Completed => 'alumno.practice-tracking.index',
        };
    }
}
