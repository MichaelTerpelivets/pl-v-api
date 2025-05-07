<?php

namespace App\Enums;

enum Category: string
{
    case Electronics = 'electronics';
    case Clothing = 'clothing';
    case Toys = 'toys';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
