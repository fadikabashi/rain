<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Storefront customer areas (not admin): named route `login` points at /admin/login.
            if ($request->is('account', 'account/*', 'quote-requests', 'quote-requests/*')) {
                return route('customer.login');
            }

            return route('login');
        }
    }
}
