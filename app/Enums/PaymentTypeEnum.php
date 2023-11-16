<?php

namespace App\Enums;

enum PaymentTypeEnum: string
{
    case CASH_ON_DELIVERY = 'CASH_ON_DELIVERY';
    case ONLINE_PAYMENT = 'ONLINE_PAYMENT';
}
