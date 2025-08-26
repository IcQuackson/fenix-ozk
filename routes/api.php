<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| For now, use the session "auth" middleware. If you later add Sanctum, switch
| to auth:sanctum. Keep the named throttle and fenix middleware.
*/

Route::middleware(['auth', 'fenix', 'throttle:fenix-api'])
    ->prefix('v1')
    ->group(function () {
        Route::get('/courses', [CourseController::class, 'apiIndex'])
            ->name('api.courses.index');
        Route::get('/courses/{id}', [CourseController::class, 'apiShow'])->name('api.courses.show');
        Route::get('/courses/{id}/evaluations', [CourseController::class, 'apiEvaluations'])->name('api.courses.evaluations');
        Route::get('/courses/{id}/groups', [CourseController::class, 'apiGroups'])->name('api.courses.groups');
        Route::get('/courses/{id}/schedule', [CourseController::class, 'apiSchedule'])->name('api.courses.schedule');
        Route::get('/courses/{id}/students', [CourseController::class, 'apiStudents'])->name('api.courses.students');
    });
