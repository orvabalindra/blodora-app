<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('login', function (Request $request): array {
            $username = Str::transliterate(Str::lower(trim((string) $request->input('username', ''))));

            return [
                Limit::perMinute(5)->by('login:'.$username.'|'.$request->ip()),
                Limit::perMinute(30)->by('login-ip:'.$request->ip()),
            ];
        });

        RateLimiter::for('password-reset', function (Request $request): array {
            $email = Str::transliterate(Str::lower(trim((string) $request->input('email', ''))));

            return [
                Limit::perMinute(3)->by('password-reset:'.$email.'|'.$request->ip()),
                Limit::perMinute(10)->by('password-reset-ip:'.$request->ip()),
            ];
        });

        RateLimiter::for('register', function (Request $request): array {
            $email = Str::transliterate(Str::lower(trim((string) $request->input('email', ''))));

            return [
                Limit::perMinute(5)->by('register:'.$email.'|'.$request->ip()),
                Limit::perHour(10)->by('register-ip:'.$request->ip()),
            ];
        });
    }
}
