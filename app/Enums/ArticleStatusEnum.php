<?php

namespace App\Enums;

enum ArticleStatusEnum: string
{
    case PUBLISHED = 'PUBLISHED';
    case UNPUBLISHED = 'UNPUBLISHED';
    case DELETED = 'DELETED';
}
