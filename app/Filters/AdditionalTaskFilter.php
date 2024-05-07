<?php

namespace App\Filters;

class AdditionalTaskFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $searchRelations = [
        'assignee' => ['name'],
        'auhor' => ['name'],
    ];

    /**
     * Searchable columns of the table
     *
     * @var array
     */
    public $searchColumns = ['title', 'description'];

    /**
     * Filter by author
     */
    public function author($value)
    {
        $this->where('created_by', $value);
    }

    /**
     * Filter by assignee
     */
    public function assignee($value)
    {
        $this->where('user_id', $value);
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

    /**
     * Filter by dueDate
     */
    public function submitDate($value)
    {
        $this->whereDate('submit_date', $value);
    }
    
}
