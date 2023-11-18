<?php

namespace App\Enums;

enum ValidationEnum: string
{
    case IMAGE = 'jpg,png,jpeg,gif,svg';
}
