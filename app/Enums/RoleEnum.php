<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'SUPER_ADMIN';
    case MOBILE_USER = 'MOBILE_USER';
    case EDITOR = 'EDITOR';
    case VIEWER = 'VIEWER';
}