<?php

namespace App\Enums;

enum SentMailTypesEnum: int
{
    case NORMAL = 1;
    case IMPORTANT = 2;
    case ALERT = 3;
}
