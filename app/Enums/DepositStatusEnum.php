<?php

namespace App\Enums;

enum DepositStatusEnum: string
{
    case DEPOSIT_PAYMENT_ACCEPTED = 'DEPOSIT_PAYMENT_ACCEPTED';
    case DEPOSIT_PENDING = 'DEPOSIT_PENDING';
    case DEPOSIT_REJECT = 'DEPOSIT_REJECT';
    case DEPOSIT_EXPIRED = 'DEPOSIT_EXPIRED';
}
