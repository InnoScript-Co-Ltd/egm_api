<?php

namespace App\Enums;

enum MembershipOrderStatusEnum: string
{
    case MEMBER_WALLET = 'MEMBER_WALLET';
    case ONLINE_PAYMENT = 'ONLINE_PAYMENT';
    case CASH = 'CASH';
}
