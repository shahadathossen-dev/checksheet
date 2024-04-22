<?php

namespace App\Models;

class Country extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'country_calling_codes' => 'json',
        'currencies' => 'json',
        'languages' => 'json',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'createdAtFormatted', 'updatedAtFormatted',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        //
    ];

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = [
        //
    ];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(Delegate::class);
    }
}
