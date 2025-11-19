<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();
        
        // Force HTTPS
        URL::forceScheme('https');
        
        // Fix session and CSRF for Railway proxy
        config(['session.secure' => false]);
        config(['session.same_site' => 'lax']);
        config(['session.http_only' => true]);
    }
}