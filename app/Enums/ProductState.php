<?php

namespace App\Enums;

enum ProductState: string
{
    case HIDDEN = 'HIDDEN';
    case SHOW = 'SHOW';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
