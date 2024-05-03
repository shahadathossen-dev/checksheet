<?php

namespace App\Models;

use App\Traits\Sortable;
use App\Traits\HasApproval;
use App\Traits\CamelCasing;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory, CamelCasing, Filterable, Sortable, SoftDeletes;
    // use HasApproval;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'user_id',
        'type',
        'checked_by',
        'status',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        // 'start_date' => 'date',
        // 'end_date' => 'date'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'startDateFormatted', 'endDateFormatted',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user', 'approver'];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    /**
     * Format the start_date with client timezone
     *
     * @return string
     */
    // public function getStartDateAttribute()
    // {
    //     return $this->start_date->format('Y-m-d');
    // }

    /**
     * Format the start_date with client timezone
     *
     * @return string
     */
    public function getStartDateFormattedAttribute()
    {
        return Carbon::parse($this->startDate)->format('Y, m d');
    }

    /**
     * Format the end_date at with client timezone
     *
     * @return string
     */
    public function getEndDateFormattedAttribute()
    {
        return Carbon::parse($this->endDate)->format('Y, m d');
    }
}
