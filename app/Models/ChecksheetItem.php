<?php

namespace App\Models;

use App\Traits\Sortable;
use App\Traits\CamelCasing;
use Illuminate\Support\Str;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecksheetItem extends Model
{
    use HasFactory, CamelCasing, Filterable, Sortable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'note_required',
        'checksheet_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'due_date' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'attachments',
        // 'createdAtFormatted', 'updatedAtFormatted',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        // 'checksheet'
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

    // public static function boot(): void
    // {
    //     parent::boot();

    //     // Will fire everytime an User is created
    //     static::creating(fn (Model $model) =>
    //         $model->created_by = auth()->id(),
    //     );
    // }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checksheet()
    {
        return $this->belongsTo(CheckSheet::class, 'checksheet_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskItems()
    {
        return $this->hasMany(TaskItem::class, 'checksheet_item_id');
    }

    /**
     * Format the ceated at with client timezone
     *
     * @return string
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->createdAt->format('d, M y H:i A');
    }

    /**
     * Format the update at with client timezone
     *
     * @return string
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updatedAt->format('d, M y H:i A');
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
