<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\PracticeReportAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PracticeReportAttachmentController extends Controller
{
    public function __invoke(PracticeReportAttachment $practiceReportAttachment): StreamedResponse
    {
        $user = auth()->user();
        $request = $practiceReportAttachment->version->report->practiceRequest;
        $authorized = $user?->role === UserRole::Coordinator
            || ($user?->role === UserRole::Student && $request->user_id === $user->id)
            || ($user?->role === UserRole::Tutor && $request->tutor_id === $user->id);
        abort_unless($authorized, 403);

        $name = basename(str_replace('\\', '/', $practiceReportAttachment->original_name));

        return Storage::disk('local')->download(
            $practiceReportAttachment->file_path,
            Str::limit($name, 240, ''),
            [
                'Content-Type' => $practiceReportAttachment->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }
}
