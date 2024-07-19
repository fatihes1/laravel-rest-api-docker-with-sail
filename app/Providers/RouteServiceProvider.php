<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        RateLimiter::for('products', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('offers', function (Request $request) {
            return Limit::perHour(200)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('orders', function (Request $request) {
            return Limit::perDay(500)->by($request->user()?->id ?: $request->ip());
        });

    }
}
