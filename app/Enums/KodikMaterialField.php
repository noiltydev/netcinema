<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * External source identifier a material may carry, used by the has_field filter.
 */
enum KodikMaterialField: string
{
    case KINOPOISK_ID = 'kinopoisk_id';
    case IMDB_ID = 'imdb_id';
    case MDL_ID = 'mdl_id';
    case WORLDART_LINK = 'worldart_link';
    case SHIKIMORI_ID = 'shikimori_id';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
