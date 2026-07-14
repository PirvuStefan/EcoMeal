<?php

namespace App\Enum;

enum PackageStatus : string
{
    case AVAILABLE = 'available';

    case RESERVED = 'reserved';
    case SOLD = 'sold';

}
