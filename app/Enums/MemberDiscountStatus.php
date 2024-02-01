<?php

namespace App\Enums;

enum MemberDiscountStatus: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case EXPIRED = 'EXPIRED';
    case DISABLE = 'DISABLE';
}
