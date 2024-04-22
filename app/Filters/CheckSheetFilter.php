<?php

namespace App\Filters;

class CheckSheetFilter extends Filter
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
        'author' => ['name'],
        'assignee' => ['name'],
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
     * Filter by assignee
     */
    public function userId($value)
    {
        $this->where('user_id', $value);
    }

    /**
     * Filter by author
     */
    public function createdBy($value)
    {
        $this->where('created_by', $value);
    }
    
}
