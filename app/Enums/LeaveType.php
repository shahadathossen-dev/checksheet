<?php

namespace App\Enums;

/**
 * @method static ActiveStatus GENERAL()
 * @method static ActiveStatus INDIVIDUAL()
 */
class LeaveType extends Enum
{
    private const GENERAL = 'general';
    private const INDIVIDUAL   = 'individual';
}
