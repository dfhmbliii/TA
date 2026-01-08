<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'check.role' => App\Http\Middleware\CheckRole::class,
            'backfill.last_login' => App\Http\Middleware\BackfillLastLogin::class,
        ]);

        // Apply to web group so it runs for authenticated pages
        $middleware->appendToGroup('web', [
            App\Http\Middleware\BackfillLastLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
