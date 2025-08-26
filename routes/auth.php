<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Auth Routes (Fenix-only)
|--------------------------------------------------------------------------
| We keep a minimal surface:
| - GET /login shows a page with a single "Continue with Fenix" button.
| - POST /logout signs the user out.
| Everything else redirects to /login to avoid exposing password flows.
*/

Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))->name('login');

    // Redirect any password/registration routes to login
    Route::any('register', fn() => redirect()->route('login'))->name('register');
    Route::any('forgot-password', fn() => redirect()->route('login'))->name('password.request');
    Route::any('reset-password/{token}', fn() => redirect()->route('login'))->name('password.reset');
    Route::any('verify-email', fn() => redirect()->route('login'))->name('verification.notice');
    Route::any('email/verification-notification', fn() => redirect()->route('login'))->name('verification.send');
    Route::any('confirm-password', fn() => redirect()->route('login'))->name('password.confirm');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
