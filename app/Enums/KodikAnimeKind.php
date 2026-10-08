<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikAnimeKind: string
{
    case TV = 'tv';
    case MOVIE = 'movie';
    case OVA = 'ova';
    case ONA = 'ona';
    case SPECIAL = 'special';
    case MUSIC = 'music';
    case TV_13 = 'tv_13';
    case TV_24 = 'tv_24';
    case TV_48 = 'tv_48';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
