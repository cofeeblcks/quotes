<?php

namespace App\Enums;

enum StatusEnum: int
{
    case ALL = 0;
    case REGISTER = 1;
    case APPROVE = 2;
    case UNAPPROVE = 3;
}
