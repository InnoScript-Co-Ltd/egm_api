<?php

namespace App\Enums;

enum EmailDeliveryEnum: string
{
    case FAIL = 'FAIL';
    case SUCCESS = 'SUCCESS';
    case RESEND = 'RESEND';
}
