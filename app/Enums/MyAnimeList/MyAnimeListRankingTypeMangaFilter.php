<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterRankingTypeKey;

enum MyAnimeListRankingTypeMangaFilter: string implements MyAnimeListFilterRankingTypeKey
{
    case ALL = 'all';
    case MANGA = 'manga';
    case NOVELS = 'novels';
    case ONESHOTS = 'oneshots';
    case DOUJIN = 'doujin';
    case MANHWA = 'manhwa';
    case MANHUA = 'manhua';
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
