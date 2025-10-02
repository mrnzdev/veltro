<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AuthService;
use App\Services\AuthManager;
use App\Services\LoggerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // AuthService as singleton
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService();
        });

        // AuthManager as singleton with AuthService dependency
        $this->app->singleton('auth.manager', function ($app) {
            return new AuthManager($app->make(AuthService::class));
        });

        // LoggerService as singleton
        $this->app->singleton(LoggerService::class, function ($app) {
            return LoggerService::getInstance();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
