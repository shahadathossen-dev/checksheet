<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class CheckSheetStatus extends Enum
{
    private const PENDING = 'pending';
    private const DUE   = 'due';
    private const DONE   = 'done';
}
