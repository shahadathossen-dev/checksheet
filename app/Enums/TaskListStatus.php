<?php

namespace App\Enums;

/**
 * @method static ActiveStatus PENDING()
 * @method static ActiveStatus DUE()
 * @method static ActiveStatus DONE()
 */
class TaskListStatus extends Enum
{
    private const PENDING = 'pending';
    private const DUE   = 'due';
    private const DONE   = 'done';
}
