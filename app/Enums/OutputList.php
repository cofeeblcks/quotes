<?php

namespace App\Enums;

enum OutputList: string
{
    case COLLECTION = 'collection';
    case PAGINATE = 'paginate';
    case ARRAY = 'array';
}