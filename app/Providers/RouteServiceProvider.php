<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\NewPasswordController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The path to the "login" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const LOGIN = '/login';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // if (request()->headers->get('origin') === env('FRONTEND_URL', 'localhost:3000')) {
            //     Route::group([
            //         'middleware' => ['guest:delegates'],
            //         'prefix' => config('fortify.prefix'),
            //     ], function () {
            //         Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            //             ->name('password.reset');
            //     });
            // }

            Route::get('/', function () {
                return redirect(self::LOGIN);
            });
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Load Auth routes
     *
     * @return void
     */
    protected function loadDelegateRoutes()
    {
        Route::group([
            'namespace' => 'Laravel\Fortify\Http\Controllers',
            'prefix' => config('fortify.prefix'),
            'as' => 'delegate.'
        ], function () {
            $this->loadRoutesFrom('../vendor/laravel/fortify/routes/routes.php');
        });
    }
}
