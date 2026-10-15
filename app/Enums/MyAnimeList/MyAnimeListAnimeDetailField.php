<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

/**
 * Fields the details endpoints accept on top of the node fields they already return.
 *
 * @see \App\Values\MyAnimeList\MyAnimeListFields
 */
enum MyAnimeListAnimeDetailField: string
{
    case PICTURES = 'pictures';
    case BACKGROUND = 'background';
    case RELATED_ANIME = 'related_anime';
    case RELATED_MANGA = 'related_manga';
    case RECOMMENDATIONS = 'recommendations';
    case STATISTICS = 'statistics';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
