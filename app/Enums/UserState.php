<?php

namespace App\Enums;

enum UserState: string
{
    case PENDING = 'PENDING';
    case ACTIVED = 'ACTIVED';
    case DISABLED = 'DISABLED';
    case REMOVED = 'REMOVED';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
