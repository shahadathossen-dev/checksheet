<?php

namespace App\Traits;

use App\Enums\LeaveStatus;

trait HasApproval
{
    /**
     * Scope a query to filter by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', LeaveStatus::PENDING());
    }
    
    /**
     * Scope a query to filter by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', LeaveStatus::APPROVED());
    }
}
