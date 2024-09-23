<?php

namespace App\Enums;

enum ReferralTypeEnm: string
{
    case LEVEL_FOUR_REFERRAL = 'LEVEL_FOUR_REFERRAL';
    case CLIENT_REFERRAL = 'CLIENT_REFERRAL';
    case COMMISSION_REFERRAL = 'COMMISSION_REFERRAL';
}
