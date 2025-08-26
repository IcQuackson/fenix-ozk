<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        // health: __DIR__ . '/../routes/health.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias your custom middleware(s) here. No facades!
        $middleware->alias([
            'fenix' => \App\Http\Middleware\EnsureFenixAuth::class,
        ]);

        // If you need to append global middleware or add to groups:
        // $middleware->append(\App\Http\Middleware\SomeGlobal::class);
        // $middleware->appendToGroup('web', \App\Http\Middleware\SomeWebOnly::class);
        // $middleware->appendToGroup('api', \App\Http\Middleware\SomeApiOnly::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
