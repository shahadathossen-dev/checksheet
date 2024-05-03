<?php

namespace App\Enums;

/**
 * @method static ActiveStatus PENDING()
 * @method static ActiveStatus APPROVED()
 * @method static ActiveStatus REJECTED()
 */
class LeaveStatus extends Enum
{
    private const PENDING = 'pending';
    private const APPROVED   = 'approved';
    private const REJECTED   = 'rejected';
}
