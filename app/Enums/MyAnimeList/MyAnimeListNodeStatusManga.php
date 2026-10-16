<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListNodeStatusManga: string
{
    case FINISHED_PUBLISHING = 'finished';
    case CURRENTLY_PUBLISHING = 'currently_publishing';
    case NOT_YET_PUBLISHED = 'not_yet_published';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
