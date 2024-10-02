<?php

namespace App\Enums;

enum CouponType: string
{
    case PERCENT = 'PERCENT';
    case FIXED = 'FIXED';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
