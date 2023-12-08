<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'SUPER_ADMIN';
    case GENERAL_MANAGER = 'GENERAL_MANAGER';
    case DATA_ENTRY = 'DATA_ENTRY';
    case VIEWER = 'VIEWER';
}
