<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeKind: string
{
    case TV = 'tv';
    case MOVIE = 'movie';
    case OVA = 'ova';
    case ONA = 'ona';
    case SPECIAL = 'special';
    case TV_SPECIAL = 'tv_special';
    case MUSIC = 'music';
    case PV = 'pv';
    case CM = 'cm';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
