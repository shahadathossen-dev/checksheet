<?php

namespace App\Observers;

use App\Http\Resources\ApiResource;
use App\Models\Product;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductForceDeleted;
use App\Jobs\ProductRestored;
use App\Jobs\ProductUpdated;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        // ProductCreated::dispatch($product)->delay(now()->addMinutes(5));
        ProductCreated::dispatchAfterResponse((new ApiResource($product))->resolve());
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        ProductUpdated::dispatchAfterResponse((new ApiResource($product))->resolve());
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        ProductDeleted::dispatchAfterResponse((new ApiResource($product))->resolve());
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        ProductRestored::dispatchAfterResponse((new ApiResource($product))->resolve());
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        ProductForceDeleted::dispatchAfterResponse((new ApiResource($product))->resolve());
    }
}
