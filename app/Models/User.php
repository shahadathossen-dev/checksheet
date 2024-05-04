<?php

namespace App\Models;

use App\Enums\CheckSheetType;
use App\Traits\Sortable;
use App\Traits\CamelCasing;
use Illuminate\Support\Str;
use EloquentFilter\Filterable;
use App\Traits\HasFilterOption;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,  HasFactory,  HasProfilePhoto, Notifiable,  HasRoles, HasPermissions, SoftDeletes, TwoFactorAuthenticatable;
    use CamelCasing, Filterable, HasFilterOption, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
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
        'profilePhotoUrl', 'role',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['roles'];


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
    public $sortable = ['*', 'roles.name'];

    /**
     * Get the custom permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['view', 'view-any', 'create', 'update', 'delete'];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checksheets()
    {
        return $this->hasMany(CheckSheet::class, 'user_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dailyChecksheet()
    {
        return $this->hasOne(CheckSheet::class, 'user_id')->where('type', CheckSheetType::DAILY());
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weeklyChecksheet()
    {
        return $this->hasOne(CheckSheet::class, 'user_id')->where('type', CheckSheetType::WEEKLY());
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function monthlyChecksheet()
    {
        return $this->hasOne(CheckSheet::class, 'user_id')->where('type', CheckSheetType::MONTHLY());
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checksheetTemplates()
    {
        return $this->hasMany(CheckSheet::class, 'submitted_by');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tasklists()
    {
        return $this->hasMany(TaskList::class, 'user_id');
    }

    /**
     * Scope a query without super admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutSuperAdmin($query)
    {
        return $query->whereHas('roles', function ($role) {
            $role->where('name', '!=', Role::SUPER_ADMIN);
        });
    }

    /**
     * Scope a query with super admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuperAdmin($query)
    {
        return $query->whereHas('roles', function ($role) {
            $role->where('name', Role::SUPER_ADMIN);
        });
    }


    /**
     * Scope a query with admins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminUsers($query)
    {
        return $query->whereHas('roles', function ($role) {
            $role->whereIn('name', [Role::SUPER_ADMIN, Role::ADMIN]);
        });
    }
    /**
     * Determines if the User is a Super admin
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }


    /**
     * Get the role name attribute
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

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
    public function getCountryNameAttribute()
    {
        return $this->country ? $this->country->name : 'Bangladesh';
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
     * Get the human readable name of the resource
     *
     * @return string
     */
    public static function readableName()
    {
        $string = Str::kebab((new \ReflectionClass(get_called_class()))->getShortName());
        return Str::plural($string);
    }
}
