<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StudentPracticeDestination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, StudentPracticeDestination $studentPracticeDestination): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son válidas.',
            ]);
        }

        $user = Auth::user();
        $destination = match ($user?->role) {
            UserRole::Coordinator => 'coordinacion.practice-tracking.index',
            UserRole::Supervisor => $user->email_verified_at && $user->company_id !== null
                    ? 'supervisor.practice-tracking.index'
                    : null,
            UserRole::Tutor => 'tutor.practice-reports.index',
            UserRole::Student => app()->environment(['local', 'testing']) && $user instanceof User
                ? $studentPracticeDestination->routeFor($user)
                : null,
            default => null,
        };

        if ($destination === null) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son válidas.',
            ]);
        }

        if ($user instanceof User && $user->role === UserRole::Student) {
            $request->session()->forget('url.intended');
        }

        $request->session()->regenerate();

        return redirect()->route($destination);
    }
}
