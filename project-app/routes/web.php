<?php

use App\Enums\UserRole;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SamlSessionController;
use App\Http\Controllers\Coordinator\PracticeAcceptanceFileController;
use App\Http\Controllers\Supervisor\InvitationController;
use App\Http\Middleware\EnsureCoordinator;
use App\Http\Middleware\EnsureSupervisor;
use App\Livewire\Companies\Index as CompanyCatalog;
use App\Livewire\Coordinator\Companies\InviteSupervisor as CoordinatorInviteSupervisor;
use App\Livewire\Coordinator\PracticeAcceptances\Index as CoordinatorPracticeAcceptances;
use App\Livewire\Coordinator\PracticeAcceptances\Review as CoordinatorPracticeAcceptanceReview;
use App\Livewire\Coordinator\PracticeLetters\PrintLetter as PracticeLetterPrinter;
use App\Livewire\Coordinator\PracticeRequests\Index as CoordinatorPracticeRequests;
use App\Livewire\Coordinator\PracticeRequests\Review as CoordinatorPracticeRequestReview;
use App\Livewire\Student\Companies\Index as StudentCompanyCatalog;
use App\Livewire\Student\PracticeLetters\Show as StudentPracticeLetter;
use App\Livewire\Student\PracticeRequests\Show as StudentPracticeRequest;
use App\Livewire\Supervisor\PracticeAcceptances\Upload as SupervisorPracticeAcceptanceUpload;
use App\Livewire\Supervisor\PracticeLetters\Index as SupervisorPracticeLetters;
use App\Livewire\Supervisor\PracticeLetters\Show as SupervisorPracticeLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('login.store');
Route::get('/invitacion-supervisor/{token}', [InvitationController::class, 'show'])
    ->middleware('throttle:10,1')
    ->name('supervisor.invitations.accept');
Route::post('/invitacion-supervisor/{token}', [InvitationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('supervisor.invitations.store');

Route::get('/auth/saml/login', [SamlSessionController::class, 'login'])->name('auth.saml.login');
Route::post('/auth/saml/acs', [SamlSessionController::class, 'assertionConsumer'])->name('auth.saml.acs');
Route::get('/auth/saml/metadata', [SamlSessionController::class, 'metadata'])->name('auth.saml.metadata');

if (app()->environment(['local', 'testing'])) {
    Route::get('/auth/saml/simulate', [SamlSessionController::class, 'simulate'])
        ->name('auth.saml.simulate');
}

Route::middleware(['auth', EnsureCoordinator::class])
    ->prefix('coordinacion')
    ->name('coordinacion.')
    ->group(function (): void {
        Route::get('/empresas', CompanyCatalog::class)->name('companies.index');
        Route::get('/empresas/{company}/supervisores/invitar', CoordinatorInviteSupervisor::class)
            ->name('companies.supervisors.invite');
        Route::get('/solicitudes', CoordinatorPracticeRequests::class)->name('practice-requests.index');
        Route::get('/solicitudes/{practiceRequest}', CoordinatorPracticeRequestReview::class)
            ->name('practice-requests.review');
        Route::get('/cartas/{practiceLetter}/imprimir', PracticeLetterPrinter::class)
            ->name('practice-letters.print');
        Route::get('/aceptaciones', CoordinatorPracticeAcceptances::class)
            ->name('practice-acceptances.index');
        Route::get('/aceptaciones/{practiceAcceptance}', CoordinatorPracticeAcceptanceReview::class)
            ->name('practice-acceptances.review');
        Route::get('/aceptaciones/{practiceAcceptance}/archivo', PracticeAcceptanceFileController::class)
            ->name('practice-acceptances.file');

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });

Route::middleware(['auth', EnsureSupervisor::class])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function (): void {
        Route::get('/cartas', SupervisorPracticeLetters::class)->name('practice-letters.index');
        Route::get('/cartas/{practiceLetter}', SupervisorPracticeLetter::class)->name('practice-letters.show');
        Route::get('/cartas/{practiceLetter}/aceptacion', SupervisorPracticeAcceptanceUpload::class)
            ->name('practice-acceptances.upload');
        Route::get('/aceptaciones/{practiceAcceptance}/archivo', PracticeAcceptanceFileController::class)
            ->name('practice-acceptances.file');

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });

Route::middleware('auth')
    ->prefix('alumno')
    ->name('alumno.')
    ->group(function (): void {
        Route::get('/empresas', StudentCompanyCatalog::class)->name('companies.index');
        Route::get('/solicitud', StudentPracticeRequest::class)->name('practice-request.show');
        Route::get('/cartas/{practiceLetter}', StudentPracticeLetter::class)->name('practice-letters.show');

        Route::post('/logout', function (Request $request) {
            abort_unless($request->user()?->role === UserRole::Student, 403);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });
