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
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/evaluations', [CourseController::class, 'evaluations'])->name('courses.evaluations');
    Route::get('/courses/{id}/groups', [CourseController::class, 'groups'])->name('courses.groups');
    Route::get('/courses/{id}/schedule', [CourseController::class, 'schedule'])->name('courses.schedule');
    Route::get('/courses/{id}/students', [CourseController::class, 'students'])->name('courses.students');
});

// Auth routes (login/logout + redirects to /login for disabled flows)
require __DIR__ . '/auth.php';
