<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\PracticeTerminationLetter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PracticeTerminationLetterFileController extends Controller
{
    public function __invoke(PracticeTerminationLetter $practiceTerminationLetter): StreamedResponse
    {
        $user = auth()->user();
        $request = $practiceTerminationLetter->practiceRequest;
        $authorized = $user?->role === UserRole::Coordinator
            || ($user?->role === UserRole::Student && $request->user_id === $user->id);
        abort_unless($authorized, 403);

        $name = basename(str_replace('\\', '/', $practiceTerminationLetter->original_name));

        return Storage::disk('local')->download(
            $practiceTerminationLetter->file_path,
            Str::limit($name, 240, ''),
            [
                'Content-Type' => $practiceTerminationLetter->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }
}
