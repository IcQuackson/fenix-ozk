<?php

use Illuminate\Http\Request;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'fenix', 'throttle:120,1'])->group(function () {
	Route::get('/courses', [CourseController::class, 'apiIndex'])->name('api.courses.index');
});
