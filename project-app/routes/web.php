<?php

use App\Enums\UserRole;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SamlSessionController;
use App\Http\Controllers\Coordinator\PracticeAcceptanceFileController;
use App\Http\Controllers\PracticeReportAttachmentController;
use App\Http\Controllers\Supervisor\InvitationController;
use App\Http\Middleware\EnsureCoordinator;
use App\Http\Middleware\EnsureSupervisor;
use App\Http\Middleware\EnsureTutor;
use App\Livewire\Companies\Index as CompanyCatalog;
use App\Livewire\Coordinator\Companies\InviteSupervisor as CoordinatorInviteSupervisor;
use App\Livewire\Coordinator\PracticeAcceptances\Index as CoordinatorPracticeAcceptances;
use App\Livewire\Coordinator\PracticeAcceptances\Review as CoordinatorPracticeAcceptanceReview;
use App\Livewire\Coordinator\PracticeEvaluations\Index as CoordinatorPracticeEvaluations;
use App\Livewire\Coordinator\PracticeLetters\PrintLetter as PracticeLetterPrinter;
use App\Livewire\Coordinator\PracticePlans\Index as CoordinatorPracticePlans;
use App\Livewire\Coordinator\PracticePlans\Review as CoordinatorPracticePlanReview;
use App\Livewire\Coordinator\PracticeReports\Index as CoordinatorPracticeReports;
use App\Livewire\Coordinator\PracticeRequests\Index as CoordinatorPracticeRequests;
use App\Livewire\Coordinator\PracticeRequests\Review as CoordinatorPracticeRequestReview;
use App\Livewire\Coordinator\Rubrics\Manage as CoordinatorRubrics;
use App\Livewire\PracticeEvaluations\Show as PracticeEvaluationShow;
use App\Livewire\Student\Companies\Index as StudentCompanyCatalog;
use App\Livewire\Student\PracticeLetters\Show as StudentPracticeLetter;
use App\Livewire\Student\PracticePlans\Show as StudentPracticePlan;
use App\Livewire\Student\PracticeReports\Show as StudentPracticeReport;
use App\Livewire\Student\PracticeRequests\Show as StudentPracticeRequest;
use App\Livewire\Supervisor\PracticeAcceptances\Upload as SupervisorPracticeAcceptanceUpload;
use App\Livewire\Supervisor\PracticeLetters\Index as SupervisorPracticeLetters;
use App\Livewire\Supervisor\PracticeLetters\Show as SupervisorPracticeLetter;
use App\Livewire\Supervisor\PracticePlans\Index as SupervisorPracticePlans;
use App\Livewire\Supervisor\PracticePlans\Review as SupervisorPracticePlanReview;
use App\Livewire\Tutor\PracticeEvaluations\Index as TutorPracticeEvaluations;
use App\Livewire\Tutor\PracticeEvaluations\Manage as TutorPracticeEvaluationManage;
use App\Livewire\Tutor\PracticeReports\Index as TutorPracticeReports;
use App\Livewire\Tutor\PracticeReports\Review as TutorPracticeReportReview;
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
        Route::get('/planes-trabajo', CoordinatorPracticePlans::class)->name('practice-plans.index');
        Route::get('/planes-trabajo/{practicePlanVersion}', CoordinatorPracticePlanReview::class)
            ->name('practice-plans.review');
        Route::get('/reportes-finales', CoordinatorPracticeReports::class)->name('practice-reports.index');
        Route::get('/evaluaciones-tutor', CoordinatorPracticeEvaluations::class)->name('practice-evaluations.index');
        Route::get('/evaluaciones-tutor/{practiceEvaluation}', PracticeEvaluationShow::class)->name('practice-evaluations.show');
        Route::get('/rubricas', CoordinatorRubrics::class)->name('rubrics.manage');

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
        Route::get('/planes-trabajo', SupervisorPracticePlans::class)->name('practice-plans.index');
        Route::get('/planes-trabajo/{practicePlanVersion}', SupervisorPracticePlanReview::class)
            ->name('practice-plans.review');

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });

Route::middleware(['auth', EnsureTutor::class])
    ->prefix('tutor')
    ->name('tutor.')
    ->group(function (): void {
        Route::get('/reportes-finales', TutorPracticeReports::class)->name('practice-reports.index');
        Route::get('/reportes-finales/{practiceReportVersion}', TutorPracticeReportReview::class)
            ->name('practice-reports.review');
        Route::get('/evaluaciones', TutorPracticeEvaluations::class)->name('practice-evaluations.index');
        Route::get('/evaluaciones/{practiceRequest}/gestionar', TutorPracticeEvaluationManage::class)
            ->name('practice-evaluations.manage');
        Route::get('/evaluaciones/{practiceEvaluation}', PracticeEvaluationShow::class)
            ->name('practice-evaluations.show');

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
        Route::get('/solicitudes/{practiceRequest}/plan-trabajo', StudentPracticePlan::class)
            ->name('practice-plans.show');
        Route::get('/solicitudes/{practiceRequest}/reporte-final', StudentPracticeReport::class)
            ->name('practice-reports.show');
        Route::get('/evaluaciones/{practiceEvaluation}', PracticeEvaluationShow::class)
            ->name('practice-evaluations.show');

        Route::post('/logout', function (Request $request) {
            abort_unless($request->user()?->role === UserRole::Student, 403);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });

Route::middleware('auth')
    ->get('/anexos-reporte/{practiceReportAttachment}', PracticeReportAttachmentController::class)
    ->name('practice-reports.attachments.download');
