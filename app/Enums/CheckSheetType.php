<?php

namespace App\Enums;

/**
 * @method static ActiveStatus DAILY()
 * @method static ActiveStatus WEEKLY()
 * @method static ActiveStatus MONTHLY()
 */
class CheckSheetType extends Enum
{
    private const DAILY = 'daily';
    private const WEEKLY   = 'weekly';
    private const MONTHLY   = 'monthly';
}
