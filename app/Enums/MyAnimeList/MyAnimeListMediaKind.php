<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

/**
 * Media kind an endpoint addresses, which decides whose nested field names apply.
 *
 * @see \App\Values\MyAnimeList\MyAnimeListFields
 */
enum MyAnimeListMediaKind: string
{
    case ANIME = 'anime';
    case MANGA = 'manga';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
