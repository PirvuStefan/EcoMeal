<?php

namespace App\Enum;

enum PackageStatus : string
{
    case AVAILABLE = 'available';

    case RESERVED = 'reserved';

    case MYSTERY = 'mystery';
    case SOLD = 'sold';

}
