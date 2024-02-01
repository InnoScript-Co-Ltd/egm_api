<?php

namespace App\Enums;

enum MemberStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case EXPIRED = 'EXPIRED';
    case DISABLE = 'DISABLE';
}
