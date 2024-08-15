<?php

namespace App\Enums;

enum DepositStatusEnum: string
{
    case PAYMENT_ACCEPTED = 'PAYMENT_ACCEPTED';
    case PENDING = 'PENDING';
    case REJECT = 'REJECT';
}
