<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ForceHttps;

/**
 * Configure the application.
 * 
 * This script configures the application with routing, middleware, and exception handling.
 * It uses the basePath as the directory one level above the current directory.
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php', // Set the path for web routes
        commands: __DIR__ . '/../routes/console.php', // Set the path for console commands
        health: '/up', // Set the health check route
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceHttps::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configure exception handling (currently empty)
    })
    ->create();
