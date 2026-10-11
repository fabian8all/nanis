<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SamlSessionController;
use App\Http\Middleware\EnsureCoordinator;
use App\Livewire\Companies\Index as CompanyCatalog;
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

        Route::post('/logout', function (Request $request) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        })->name('logout');
    });
