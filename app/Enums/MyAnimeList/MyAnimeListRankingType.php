<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListRankingType: string
{
    case ALL = 'all';
    case AIRING = 'airing';
    case UPCOMING = 'upcoming';
    case TV = 'tv';
    case OVA = 'ova';
    case MOVIE = 'movie';
    case SPECIAL = 'special';
    case BY_POPULARITY = 'bypopularity';
    case FAVORITE = 'favorite';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
