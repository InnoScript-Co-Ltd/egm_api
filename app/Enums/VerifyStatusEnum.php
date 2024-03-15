<?php

namespace App\Enums;

enum VerifyStatusEnum: string
{
    case APPROVE = 'APPROVE';
    case CHANGE_PASSWORD = 'CHANGE_PASSWORD';

    case RESET_PASSWORD = 'RESET_PASSWORD';
}
