<?php

namespace App\Enums;

/**
 * @method static ActiveStatus INACTIVE()
 * @method static ActiveStatus ACTIVE()
 */
class CheckSheetType extends Enum
{
    private const DAILY = 'daily';
    private const WEEKLY   = 'weekly';
    private const MONTHLY   = 'monthly';
}
