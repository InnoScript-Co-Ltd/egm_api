<?php

namespace App\Enums;

enum MemberDiscountStatus: string
{
    case ACTIVE = 'ACTIVE';
    case DISABLE = 'PENDING';
    case PENDING = 'EXPIRED';
}
