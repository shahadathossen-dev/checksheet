<?php

namespace App\Filters;

class TaskListFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $searchRelations = [
        'checksheet' => ['title'],
        'user' => ['name'],
    ];

    /**
     * Searchable columns of the table
     *
     * @var array
     */
    public $searchColumns = [];

    /**
     * Filter by checksheet
     */
    public function checksheetId($value)
    {
        $this->where('checksheet_id', $value);
    }

    /**
     * Filter by user
     */
    public function submittedBy($value)
    {
        $this->where('submitted_by', $value);
    }

    /**
     * Filter by status
     */
    public function status($value)
    {
        $this->where('status', $value);
    }
    
}
