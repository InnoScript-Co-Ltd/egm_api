<?php

namespace App\Enums;

enum InvestorStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case CHECKING = 'CHECKING';
    case PENDING = 'PENDING';
    case REJECT = 'REJECT';
    case BLOCK = 'BLOCK';
    case DELETED = 'DELETED';
}
