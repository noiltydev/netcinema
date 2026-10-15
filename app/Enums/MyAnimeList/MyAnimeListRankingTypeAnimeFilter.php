<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterRankingTypeKey;

enum MyAnimeListRankingTypeAnimeFilter: string implements MyAnimeListFilterRankingTypeKey
{
    case ALL = 'all';
    case AIRING = 'airing';
    case UPCOMING = 'upcoming';
    case TV = 'tv';
    case OVA = 'ova';
    case MOVIE = 'movie';
    case SPECIAL = 'special';
    case BYPOPULARITY = 'bypopularity';
    case FAVORITE = 'favorite';

    public static function queryRankingTypeKey(): string
    {
        return 'ranking_type';
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
