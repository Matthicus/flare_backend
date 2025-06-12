<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

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
    ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
    });
       
    if (app()->environment('local')) {
        // Disable Sanctum CSRF and session cookie enforcement in local dev
        Config::set('sanctum.stateful', []);
        Config::set('session.driver', 'array'); // optional: avoid creating session files
    }
    
    // Force session cookie settings for production cross-domain
    if (app()->environment('production')) {
        Config::set('session.same_site', 'none');
        Config::set('session.secure', true);
        Config::set('session.domain', null);
    }
}
}
