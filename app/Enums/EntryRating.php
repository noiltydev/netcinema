<?php

declare(strict_types=1);

namespace App\Enums;

enum EntryRating: string
{
    case G = 'g';
    case PG = 'pg';
    case PG_13 = 'pg_13';
    case R = 'r';
    case R_PLUS = 'r_plus';
    case RX = 'rx';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
