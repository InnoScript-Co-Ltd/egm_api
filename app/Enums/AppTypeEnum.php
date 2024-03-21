<?php

namespace App\Enums;

enum AppTypeEnum: string
{
    case GSCEXPORT = 'GSCEXPORT';
    case MPE = 'MPE';
    case GLODEN_MEMBERSHIP = 'GLODEN_MEMBERSHIP';
    case GSCEXPORT_MERCHANT = 'GSCEXPORT_MERCHANT';
    case MPE_MERCHANT = 'MPE_MERCHANT';
}
