<?php

namespace App\Enums;

enum MemberDiscountStatus: string
{
    case ACTIVE = 'ACTIVE';
    case DISABLE = 'DISABLE';
    case PENDING = 'EXPIRED';
}
