<?php

namespace App\Traits;

trait TrashedScope
{
    /**
     * Scope a query to get with transed or only transhed resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrashed($query)
    {
        if(!empty(request()->get('trashed')) && request()->get('trashed') == 'with'){
            return $query->withTrashed();
        }

        if(!empty(request()->get('trashed')) && request()->get('trashed') == 'only'){
            return $query->onlyTrashed();
        }

        return $query;
    }
}
