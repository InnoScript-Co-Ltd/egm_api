<?php

namespace App\Enums;

enum KycStatusEnum: string
{
    case FULL_KYC = 'FULL_KYC';
    case CHECKING = 'CHECKING';
    case REJECT = 'REJECT';
}
