<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware (web group)
        $middleware->web(append: [
            \App\Http\Middleware\LanguageManager::class,
        ]);

        // Performance monitoring middleware (only when enabled via environment)
        // Note: Using env() directly as config() is not available during bootstrap
        if (env('ENABLE_PERFORMANCE_LOGGING', false)) {
            $middleware->web(append: [
                \App\Http\Middleware\LogPerformance::class,
            ]);
        }

        // Slow query logging middleware (only when enabled via environment)
        if (env('ENABLE_SLOW_QUERY_LOGGING', false)) {
            $middleware->web(append: [
                \App\Http\Middleware\LogSlowQueries::class,
            ]);
        }

        // Route middleware aliases (from Http/Kernel.php)
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Log all exceptions with context
        // Note: Using try-catch to prevent issues if LoggingService is not available
        $exceptions->report(function (\Throwable $e) {
            try {
                if (app()->bound(\App\Services\LoggingService::class)) {
                    $loggingService = app(\App\Services\LoggingService::class);
                    $loggingService->logOrder('error', 'Unhandled exception: ' . $e->getMessage(), [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            } catch (\Exception $loggingException) {
                // Fallback to default Laravel logging if LoggingService fails
                \Illuminate\Support\Facades\Log::error('Exception occurred', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        });
    })->create();
