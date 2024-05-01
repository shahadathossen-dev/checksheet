<?php

namespace App\Providers;

use App\Events\DueStatusEvent;
use App\Jobs\NewDelegateRegistered;
use App\Listeners\DueStatusEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        DueStatusEvent::class => [
            DueStatusEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        App::bindMethod(NewDelegateRegistered::class . '@handle', fn($job) => $job->handle());

        // Product events
        // App::bindMethod(ProductCreated::class . '@handle', fn($job) => $job->handle());
        // App::bindMethod(ProductUpdated::class . '@handle', fn($job) => $job->handle());
        // App::bindMethod(ProductDeleted::class . '@handle', fn($job) => $job->handle());
        // App::bindMethod(ProductRestored::class . '@handle', fn($job) => $job->handle());
        // App::bindMethod(ProductForceDeleted::class . '@handle', fn($job) => $job->handle());
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
