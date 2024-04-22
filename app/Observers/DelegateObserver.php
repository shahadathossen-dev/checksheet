<?php

namespace App\Observers;

use App\Http\Resources\ApiResource;
use App\Models\Delegate;
use App\Jobs\NewDelegateRegistered;

class DelegateObserver
{
    /**
     * Handle the Delegate "created" event.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return void
     */
    public function created(Delegate $delegate)
    {
        // NewDelegateRegistered::dispatch($delegate)->delay(now()->addMinutes(5));
        NewDelegateRegistered::dispatchAfterResponse((new ApiResource($delegate))->resolve());
    }

    /**
     * Handle the Delegate "updated" event.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return void
     */
    public function updated(Delegate $delegate)
    {
        //
    }

    /**
     * Handle the Delegate "deleted" event.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return void
     */
    public function deleted(Delegate $delegate)
    {
        //
    }

    /**
     * Handle the Delegate "restored" event.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return void
     */
    public function restored(Delegate $delegate)
    {
        //
    }

    /**
     * Handle the Delegate "force deleted" event.
     *
     * @param  \App\Models\Delegate  $delegate
     * @return void
     */
    public function forceDeleted(Delegate $delegate)
    {
        //
    }
}
