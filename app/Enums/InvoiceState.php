<?php

namespace App\Enums;

enum InvoiceState: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case PAID = 'PAID';
    case CANCELED = 'CANCELED';

    public static function getValues(): array
    {
        return array_map(fn($state) => $state->value, self::cases());
    }
}
