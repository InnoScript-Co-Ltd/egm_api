<?php

namespace App\Enums;

enum BankAccountStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case DISABLE = 'BLOCK';
    case DELETED = 'DELETED';
}
