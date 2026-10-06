<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Repository Interfaces
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\OrderRepositoryInterface::class,
            \App\Repositories\OrderRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\CategoryRepositoryInterface::class,
            \App\Repositories\CategoryRepository::class
        );
        
        // Third-party service providers that may not auto-discover
        // Most Laravel 11 packages auto-discover, but register here if needed
        // Uncomment if packages don't auto-discover after upgrade:
        
        // $this->app->register(\Davmixcool\MetaManager\MetaServiceProvider::class);
        // $this->app->register(\A6digital\Image\DefaultProfileImageServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $rootUrl = (string) config('app.url');
        if (str_starts_with($rootUrl, 'https://')) {
            URL::forceScheme('https');
        }
    }
}
