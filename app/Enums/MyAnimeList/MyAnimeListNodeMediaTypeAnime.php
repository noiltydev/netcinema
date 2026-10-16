<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListNodeMediaTypeAnime: string
{
    case UNKNOWN = 'unknown';
    case TV = 'tv';
    case OVA = 'ova';
    case MOVIE = 'movie';
    case SPECIAL = 'special';
    case ONA = 'ona';
    case MUSIC = 'music';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
