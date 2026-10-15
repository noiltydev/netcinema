<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListNodeMediaTypeManga: string
{
    case UNKNOWN = 'unknown';
    case MANGA = 'manga';
    case NOVEL = 'novel';
    case ONES_HOT = 'one_shot';
    case DOUJINSHI = 'doujinshi';
    case MANHWA = 'manhwa';
    case MANHUA = 'manhua';
    case OEL = 'oel';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
