<?php

namespace App\Enums;

enum PackageBuyStatusEnum: string
{
    case REQUEST = 'REQUEST';
    case APPROVED = 'APPROVED';
    case PROCESS = 'PROCESS';
    case REJECT = 'REJECT';
    case SUCCESS = 'SUCCESS';
}
