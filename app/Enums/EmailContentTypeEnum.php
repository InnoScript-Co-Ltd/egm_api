<?php

namespace App\Enums;

enum EmailContentTypeEnum: string
{
    case PARTNER_ACCOUNT_OPENING = 'PARTNER_ACCOUNT_OPENING';
    case PARTNER_KYC_REJECT = 'PARTNER_KYC_REJECT';
    case PARTNER_KYC_APPROVE = 'PARTNER_KYC_APPROVE';
}
