<?php

namespace App\Models;

class Attribute extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
