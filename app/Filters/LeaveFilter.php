<?php

namespace App\Filters;

class LeaveFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $searchRelations = [
        'user' => ['name'],
        'approver' => ['name'],
    ];

    /**
     * Searchable columns of the table
     *
     * @var array
     */
    public $searchColumns = ['title', 'description'];

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
     * Filter by user
     */
    public function user($value)
    {
        $this->where('user_id', $value);
    }

    /**
     * Filter by approver
     */
    public function approver($value)
    {
        $this->where('checked_by', $value);
    }
    
}
