<?php

namespace App\Models;

use App\Models\Country;
use App\Traits\Sortable;
use App\Traits\CamelCasing;
use Illuminate\Support\Str;
use EloquentFilter\Filterable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Delegate extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory,  HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;
    use CamelCasing, Filterable, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'country_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'createdAtFormatted', 'updatedAtFormatted',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'country'
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 25;

    /**
     * Available sortable fields
     *
     * @var array
     */
    public $sortable = ['*'];

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['view', 'view-any', 'create', 'update'];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get user's timezone
     *
     * @return string
     */
    public function getTimezoneAttribute()
    {
        return $this->country ? $this->country->zoneName : 'Asia/Dhaka';
    }

    /**
     * Format the ceated at with client timezone
     *
     * @return string
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->createdAt->setTimezone(Auth::check() ? Auth::user()->timezone : 'Asia/Dhaka')->format('d, M y H:i A');
    }

    /**
     * Format the update at with client timezone
     *
     * @return string
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updatedAt->setTimezone(Auth::check() ? Auth::user()->timezone : 'Asia/Dhaka')->format('d, M y H:i A');
    }

    /**
     * Get the human readable name of the resource
     *
     * @return string
     */
    public static function readableName()
    {
        $string = Str::kebab((new \ReflectionClass(get_called_class()))->getShortName());
        return Str::plural($string);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }
}
