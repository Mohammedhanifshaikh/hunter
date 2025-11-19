<?php

use App\Http\Middleware\AdminAuthentication;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => AdminAuthentication::class,
        ]);
        
        // Temporarily disable CSRF for login to test
        $middleware->validateCsrfTokens(except: [
            '/login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();