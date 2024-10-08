<?php

namespace App\Enums;

enum RepaymentStatusEnum: string
{
    case AVAILABLE_WITHDRAW = 'AVAILABLE_WITHDRAW';
    case TRANSFER_SUCCESS = 'TRANSFER_SUCCESS';
}
