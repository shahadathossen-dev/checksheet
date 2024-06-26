<?php

namespace App\Filters;

class TaskFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $searchRelations = [
        'checksheet' => ['title'],
    ];

    /**
     * Searchable columns of the table
     *
     * @var array
     */
    public $searchColumns = ['title'];

    /**
     * Filter by title
     */
    public function title($value)
    {
        $this->where('title', $value);
    }
    
}
