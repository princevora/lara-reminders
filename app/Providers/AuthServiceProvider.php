<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('access-notification-types', fn () => self::getAdminAccess());
        Gate::define('receive-notifications', fn() => auth()->guard('web')->check());
        Gate::define('send-venue-request', fn() => auth()->guard('web')->check());
    }

    private function getAdminAccess()
    {
        return auth()->guard('admin')->check();
    }
}
