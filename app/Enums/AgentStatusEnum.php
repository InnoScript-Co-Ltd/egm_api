<?php

namespace App\Enums;

enum AgentStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case REJECT = 'REJECT';
    case BLOCK = 'BLOCK';
    case DELETED = 'DELETED';
}
