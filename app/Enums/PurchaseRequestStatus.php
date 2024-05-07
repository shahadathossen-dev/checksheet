<?php

namespace App\Enums;

/**
 * @method static ActiveStatus PENDING()
 * @method static ActiveStatus DONE()
 * @method static ActiveStatus DUE()
 */
class PurchaseRequestStatus extends Enum
{
    private const PENDING = 'pending';
    private const DONE   = 'done';
    private const DUE   = 'due';
}
