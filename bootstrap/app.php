<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        // health: ...
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add/merge any aliases here
        $middleware->alias([
            'fenix' => \App\Http\Middleware\EnsureFenixAuth::class,
        ]);

        // You can also push global or group middleware here if needed.
    })
    ->withExceptions(function ($exceptions) {
        // ...
    })
    ->create();
