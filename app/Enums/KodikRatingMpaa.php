<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikRatingMpaa: string
{
    case G = 'G';
    case PG = 'PG';
    case PG_13 = 'PG-13';
    case R = 'R';
    case R_PLUS = 'R+';
    case RX = 'Rx';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
