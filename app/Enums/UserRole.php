<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'USER';
    case ADMIN = 'ADMIN';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
