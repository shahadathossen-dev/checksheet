<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticatedApiSession;
use App\Actions\Fortify\CreateNewDelegate;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config()->set('fortify.redirects.logout', RouteServiceProvider::LOGIN);

        if(request()->is('api/v1/*')) {
            config()->set('fortify.views', false);
            config()->set('fortify.prefix', 'api/v1');
            config()->set('fortify.middleware', ['api']);
        }

        if (request()->headers->get('origin') === env('FRONTEND_URL', 'localhost:3000')) {
            config()->set('fortify.prefix', 'delegate');
            config()->set('fortify.views', false);
            config()->set('fortify.guard', 'delegates');
            config()->set('fortify.passwords', 'delegates');

            config()->set('fortify.features', [
                Features::registration(),
                Features::resetPasswords(),
                Features::emailVerification(),
                Features::updateProfileInformation(),
                Features::updatePasswords(),
                // Features::twoFactorAuthentication([
                //     'confirm' => false,
                //     'confirmPassword' => false,
                // ]),
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
                $request->expectsJson() ? AuthenticatedApiSession::class : PrepareAuthenticatedSession::class,
            ]);
        });
        Fortify::createUsersUsing(request()->headers->get('origin') === env('FRONTEND_URL', 'localhost:3000') ? CreateNewDelegate::class : CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
