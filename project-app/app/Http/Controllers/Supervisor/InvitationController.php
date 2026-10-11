<?php

namespace App\Http\Controllers\Supervisor;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\SupervisorInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function show(string $token): View
    {
        $invitation = $this->findValidInvitation($token);

        return view('auth.supervisor-invitation', [
            'invitation' => $invitation,
            'token' => $token,
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        $user = DB::transaction(function () use ($data, $token): User {
            $invitation = $this->findValidInvitation($token, lock: true);
            if (User::query()->where('email', $invitation->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'Ya existe una cuenta con este correo. Solicita a Coordinación una invitación con otro correo.',
                ]);
            }

            $user = new User([
                'name' => $data['name'],
                'email' => $invitation->email,
                'password' => $data['password'],
                'role' => UserRole::Supervisor,
                'company_id' => $invitation->company_id,
            ]);
            $user->email_verified_at = now();
            $user->save();

            $invitation->update(['accepted_at' => now()]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('supervisor.practice-letters.index');
    }

    private function findValidInvitation(string $token, bool $lock = false): SupervisorInvitation
    {
        $query = SupervisorInvitation::query()
            ->with('company:id,name')
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now());

        if ($lock) {
            $query->lockForUpdate();
        }

        $invitation = $query->first();
        abort_unless($invitation, 404);

        return $invitation;
    }
}
