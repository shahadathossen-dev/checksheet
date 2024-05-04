<?php

namespace App\Filters;

class TaskItemFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $searchRelations = [
        'task' => ['title'],
    ];

    /**
     * Searchable columns of the table
     *
     * @var array
     */
    public $searchColumns = ['note'];

    /**
     * Filter by task
     */
    public function taskId($value)
    {
        $this->where('task_id', $value);
    }

    /**
     * Filter by tasklist
     */
    public function tasklistId($value)
    {
        $this->where('tasklist_id', $value);
    }
    
}
