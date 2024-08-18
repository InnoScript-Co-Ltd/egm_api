<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case DEPOSIT_PAYMENT_ACCEPTED = 'DEPOSIT_PAYMENT_ACCEPTED';
    case DEPOSIT_PENDING = 'DEPOSIT_PENDING';
    case DEPOSIT_REJECT = 'DEPOSIT_REJECT';
}
