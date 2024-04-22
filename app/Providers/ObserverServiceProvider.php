<?php

namespace App\Providers;

use App\Models\Delegate;
use App\Observers\DelegateObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Delegate::observe(DelegateObserver::class);
    }
}
