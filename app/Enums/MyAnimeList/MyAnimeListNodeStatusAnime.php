<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListNodeStatusAnime: string
{
    case FINISHED_AIRING = 'finished_airing';
    case CURRENTLY_AIRING = 'currently_airing';
    case NOT_YET_AIRED = 'not_yet_aired';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
