<?php

namespace App\Http\Controllers\Coordinator;

use App\Enums\PracticeAcceptanceStatus;
use App\Enums\PracticeRequestStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\PracticeAcceptanceSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PracticeAcceptanceFileController extends Controller
{
    public function __invoke(PracticeAcceptanceSubmission $practiceAcceptance): StreamedResponse
    {
        $user = Auth::user();
        $isCoordinator = $user?->isCoordinator() ?? false;
        $request = $practiceAcceptance->practiceRequest;
        $isAcceptedSubmission = $practiceAcceptance->status === PracticeAcceptanceStatus::Accepted
            && in_array($request->status, [PracticeRequestStatus::Approved, PracticeRequestStatus::Completed], true);
        $isStudentOwner = $user?->role === UserRole::Student
            && $request->user_id === $user->id
            && $isAcceptedSubmission;
        $isAssignedTutor = $user?->role === UserRole::Tutor
            && $request->tutor_id === $user->id
            && $isAcceptedSubmission;
        $isAssignedSupervisor = $user?->role === UserRole::Supervisor
            && $user->isSupervisor()
            && $user->email_verified_at !== null
            && $user->company_id === $request->company_id
            && in_array($request->status, [PracticeRequestStatus::Approved, PracticeRequestStatus::Completed], true)
            && $practiceAcceptance->supervisor_id === $user->id;

        abort_unless($isCoordinator || $isStudentOwner || $isAssignedTutor || $isAssignedSupervisor, 403);

        return Storage::disk('local')->download(
            $practiceAcceptance->file_path,
            sprintf('carta-aceptacion-v%d.%s', $practiceAcceptance->version, $practiceAcceptance->file_extension),
            [
                'Content-Type' => $practiceAcceptance->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }
}
