<?php

namespace App\Enums;

enum OrderStatusEnum : string
{
    case PENDING = "PENDING";
    case VERIFIED = "VERIFIED";
    case DELIVERY = "DELIVERY";
    case COMPLETE = "COMPLETE";
}