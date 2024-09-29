<?php

namespace App\Enums;

enum TokenType: string
{
    case ACCOUNT_VERIFICATION = 'ACCOUNT_VERIFICATION';
    case PASSWORD_REST = 'PASSWORD_REST';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
