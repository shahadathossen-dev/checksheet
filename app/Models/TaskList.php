<?php

namespace App\Models;

use App\Enums\TaskListStatus;
use App\Events\DueStatusEvent;
use App\Traits\Sortable;
use App\Traits\CamelCasing;
use Carbon\Carbon;
use Illuminate\Support\Str;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskList extends Model
{
    use HasFactory, CamelCasing, Filterable, Sortable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'checksheet_id',
        'type',
        'user_id',
        'due_date',
        'submitted_by',
        'submit_date',
        'status',
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
        'due_date' => 'date',
        'submit_date' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'attachments',
        'createdAtFormatted', 'updatedAtFormatted',
        'dueDateFormatted', 'submitDateFormatted',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'checksheet',
        'assignee',
        'items'
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

    public static function boot(): void
    {
        parent::boot();

        // Will fire everytime an User is created
        static::creating(function (Model $model) {
            $model->submitted_by = auth()->id();
            $model->submit_date = Carbon::today();
        });

        static::updating(function (Model $model) {
            $model->submitted_by = auth()->id();
            $model->submit_date = Carbon::today();
        });
    }

    /**
     * Define accessor for model attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // protected function type(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => ucfirst($value),
    //     );
    // }
    
    /**
     * Define accessor for model attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function dueDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    /**
     * Define accessor for model attribute
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function submitDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

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
    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function items()
    {
        return $this->hasMany(TaskItem::class, 'tasklist_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Update task list status as done
     *
     * @return \App\Models\TaskList
     */
    public function markAsDone()
    {
        $this->update(['status' => TaskListStatus::DONE()]);
    }

    /**
     * Update task list status as due
     *
     * @return \App\Models\TaskList
     */
    public function markAsDue()
    {
        $this->update(['status' => TaskListStatus::DUE()]);
    }
    
    /**
     * Handles status update
     *
     * @return \App\Models\TaskList
     */
    public function updateStatus()
    {
        // If status input by force
        if($status = request()->input('status')) {
            $this->update(['status' => $status]);
            return;
        }

        $taskItems = $this->items;
        $totalCount = $taskItems->count();
        $doneCount = $taskItems->where('done', 1)->count();

        if($doneCount == $totalCount) {
            $this->markAsDone();
        } else if($this->dueDate->diffInDays(today()) > 0) {
            $this->markAsDue();
        }

        if($this->status == TaskListStatus::DUE())
        DueStatusEvent::dispatch($this->fresh());
    }

    /**
     * Scope a query to only exclude admin role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', TaskListStatus::PENDING());
    }

    /**
     * Scope a query to only exclude admin role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDone($query)
    {
        return $query->where('status', TaskListStatus::DONE());
    }

    /**
     * Scope a query to only exclude admin role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDue($query)
    {
        return $query->where('status', TaskListStatus::DUE());
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
     * Format the update at with client timezone
     *
     * @return string
     */
    public function getDueDateFormattedAttribute()
    {
        return Carbon::parse($this->dueDate)->format('d, M Y');
    }

    /**
     * Format the update at with client timezone
     *
     * @return string
     */
    public function getSubmitDateFormattedAttribute()
    {
        return Carbon::parse($this->submitDate)->format('d, M Y');
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
