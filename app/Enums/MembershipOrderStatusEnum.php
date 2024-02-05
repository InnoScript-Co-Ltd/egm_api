<?php

namespace App\Enums;

enum MembershipOrderStatusEnum: string
{
    case SUCCESS = 'SUCCESS';
    case PENDING = 'PENDING';
    case REJECT = 'REJECT';
    case RETURN = 'RETURN';
}
