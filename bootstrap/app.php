<?php

use Illuminate\Support\Env;

Env::disablePutenv();

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckInternetConnection;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->append(CheckInternetConnection::class);

        $middleware->alias([
            'isAuthenticated' => \App\Http\Middleware\CheckSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->report(function (Throwable $e) {

            Log::error($e->getMessage(), [
                'file'   => $e->getFile(),
                'line'   => $e->getLine(),
                'url'    => request()->fullUrl(),
                'method' => request()->method(),
                'ip'     => request()->ip(),
            ]);

            return false;
        });
    })
    ->create();