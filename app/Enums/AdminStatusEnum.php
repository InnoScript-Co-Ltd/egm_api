<?php

namespace App\Enums;

enum AdminStatusEnum: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case BLOCK = 'BLOCK';
    case DELETED = 'DELETED';
}
