<?php

use App\Http\Middleware\Authorize;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(LoggedIn::class);
        $middleware->use([]);
        $middleware->alias([
            // 'subscribed' => EnsureUserIsSubscribed::class
            'authorize' => Authorize::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
