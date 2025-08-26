<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define named rate limiters here (facades are ready during provider boot)
        RateLimiter::for('fenix-api', function (Request $request) {
            return [
                Limit::perMinute(120)->by(
                    optional($request->user())->id ?: $request->ip()
                ),
            ];
        });

        // Example optional web limiter if you want to throttle SSR pages separately:
        // RateLimiter::for('fenix-web', function (Request $request) {
        //     return [ Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip()) ];
        // });
    }
}
