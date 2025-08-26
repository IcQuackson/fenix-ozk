<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FenixAuthController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Health
Route::get('/healthz', fn() => response('ok', 200))->name('healthz');

// --- Fenix OAuth (public) ---
Route::get('/fenix/connect', [FenixAuthController::class, 'connect'])->name('fenix.connect');
Route::get('/fenix/callback', [FenixAuthController::class, 'callback'])->name('fenix.callback');

// --- SSR App (auth + fenix + throttle) ---
// We use '/' as the canonical dashboard route name.
Route::middleware(['auth', 'fenix', 'throttle:60,1'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
});

// --- Optional Breeze profile pages (auth only) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (login/logout + redirects to /login for disabled flows)
require __DIR__ . '/auth.php';
