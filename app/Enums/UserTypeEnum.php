<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case CLIENT = 'CLIENT';
    case MERCHANT = 'MERCHANT';
    case ADMIN = 'ADMIN';
}
