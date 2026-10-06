<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * Note: Routes are configured in bootstrap/app.php
     * Rate limiting is configured here in the boot method.
     */
    public function boot(): void
    {
        // API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Cart operations rate limiting
        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // Checkout rate limiting
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Quote request submissions (public batch quote form)
        RateLimiter::for('quote', function (Request $request) {
            return Limit::perMinute(15)->by($request->user()?->id ?: $request->ip());
        });
    }
}
