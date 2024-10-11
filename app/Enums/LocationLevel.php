<?php

namespace App\Enums;

enum LocationLevel: string
{
    case CITY = 'CITY';
    case DISTRICT = 'DISTRICT';
    case WARD = 'WARD';

    public static function getValues(): array
    {
        return array_map(fn($level) => $level->value, self::cases());
    }
}
