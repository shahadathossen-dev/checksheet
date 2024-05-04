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
    public $searchRelations = [
        'checksheet' => ['title'],
        'assignee' => ['name'],
        'auhor' => ['name'],
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
    public function checksheet($value)
    {
        $this->where('checksheet_id', $value);
    }

    /**
     * Filter by author
     */
    public function author($value)
    {
        $this->where('submitted_by', $value);
    }

    /**
     * Filter by assignee
     */
    public function assignee($value)
    {
        $this->where('user_id', $value);
    }

    /**
     * Filter by type
     */
    public function type($value)
    {
        $this->where('type', $value);
    }

    /**
     * Filter by status
     */
    public function status($value)
    {
        $this->where('status', $value);
    }

    /**
     * Filter by dueDate
     */
    public function dueDate($value)
    {
        $this->whereDate('due_date', $value);
    }
    
}
