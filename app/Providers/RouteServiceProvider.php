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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('dashboard', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('merchant', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('dashboard')
                ->prefix('dashboard')
                ->namespace('App\Http\Controllers\Dashboard')
                ->group(base_path('routes/dashboard.php'));

            Route::middleware('api')
                ->prefix('api')
                ->namespace('App\Http\Controllers\Client')
                ->group(base_path('routes/api.php'));

            Route::middleware('merchant')
                ->prefix('merchant')
                ->namespace('App\Http\Controllers\Merchant')
                ->group(base_path('routes/merchant.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
