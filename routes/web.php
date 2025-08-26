<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
Route::get('/fenix/connect', [AuthFenixController::class, 'redirect'])->name('fenix.connect');
Route::get('/fenix/callback', [AuthFenixController::class, 'callback'])->name('fenix.callback');

Route::middleware(['auth', 'fenix', 'throttle:60,1'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
});

// OAuth connect placeholder
Route::get('/fenix/connect', fn() => 'TODO: connect to Fenix')->name('fenix.connect');
