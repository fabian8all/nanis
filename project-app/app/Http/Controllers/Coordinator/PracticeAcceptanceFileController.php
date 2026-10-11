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
        $isAssignedSupervisor = $user?->role === UserRole::Supervisor
            && $user->company_id === $request->company_id
            && $request->status === PracticeRequestStatus::Approved
            && ! $request->acceptanceSubmissions()
                ->where('status', PracticeAcceptanceStatus::Accepted->value)
                ->exists();

        abort_unless($isCoordinator || $isAssignedSupervisor, 403);

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
