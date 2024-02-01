<?php

namespace App\Enums;

enum MemberCardStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case EXPIRED = 'EXPIRED';
    case DISABLE = 'DISABLE';
}
