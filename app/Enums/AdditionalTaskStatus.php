<?php

namespace App\Enums;

/**
 * @method static ActiveStatus PENDING()
 * @method static ActiveStatus DONE()
 * @method static ActiveStatus DUE()
 */
class AdditionalTaskStatus extends Enum
{
    private const PENDING = 'pending';
    private const DONE   = 'done';
    private const DUE   = 'due';
}
