<?php

namespace App\Enums;

enum BannerState: string
{
    case HIDDEN = 'HIDDEN';
    case SHOW = 'SHOW';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
