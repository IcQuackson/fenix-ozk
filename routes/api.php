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
    });
