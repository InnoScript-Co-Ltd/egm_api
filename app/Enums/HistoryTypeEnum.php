<?php

namespace App\Enums;

enum HistoryTypeEnum: string
{
    case REPAYMENT = 'REPAYMENT';
    case DEPOSIT = 'DEPOSIT';
    case WITHDRAW = 'WITHDRAW';
    case SYSTEM = 'SYSTEM';
}
