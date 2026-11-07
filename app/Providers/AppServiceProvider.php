<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Intervention's Laravel package binds the manager under the 'image' alias only; expose it for injection too.
        $this->app->bind(\Intervention\Image\ImageManager::class, fn ($app) => $app->make(\Intervention\Image\Laravel\Facades\Image::BINDING));
    }

    public function boot(): void
    {
        Password::defaults(fn () => Password::min(8)->letters()->numbers());

        // Throttle the 25 Dec batch so we stay under the SES sending rate.
        RateLimiter::for('santa-emails', function () {
            return Limit::perMinute((int) config('letters.email_rate_per_minute'));
        });

        // Party pages: per IP and per event, to stop credit draining.
        RateLimiter::for('party-submit', function (Request $request) {
            return [
                Limit::perMinute((int) config('letters.party.rate_limit_per_ip_per_minute'))->by('ip:'.$request->ip()),
                Limit::perMinute((int) config('letters.party.rate_limit_per_event_per_minute'))->by('event:'.$request->route('slug')),
            ];
        });

        RateLimiter::for('wall-feed', fn (Request $request) => Limit::perMinute(30)->by($request->ip()));
    }
}
