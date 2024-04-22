<?php

namespace App\Models;

use App\Traits\Sortable;
use App\Traits\CamelCasing;
use EloquentFilter\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Model extends BaseModel
{
    use HasFactory, CamelCasing, Sortable, Filterable;


    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 25;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Available sortable fields
     *
     * @var array
     */
    public $sortable = ['*'];

    /**
     * Set the default sort citeria
     *
     * @var array
     */
    protected $defaultSortCriteria = ['id,desc'];

    /**
     * Get the default permissions name of the resource
     *
     * @var array
     */
    public static $permissions = ['view', 'view-any', 'create', 'update', 'delete'];

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
     * Check any relation exists with the model
     *
     * @return string
     */
    public function whereHasAny($relations)
    {
        $realationsArray = '';
        if (is_array($relations)) {
            $realationsArray = $relations;
        } else {
            $realationsArray = explode(',', $relations);
        }

        $index = 1;
        $query = $this->query();
        if (count($realationsArray) === 1) {
            $query = $query->whereHas($realationsArray[$index]);
        } else {
            do {
                if ($index === 1) {
                    $query = $this->whereHas($realationsArray[$index]);
                } else {
                    $query = $query->orWhereHas($realationsArray[$index]);
                }
                $index++;
            } while ($index <= count($realationsArray));
        }
        return $query;
    }
}
