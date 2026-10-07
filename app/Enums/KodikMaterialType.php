<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikMaterialType: string
{
    case FOREIGN_MOVIE = 'foreign-movie';
    case SOVIET_CARTOON = 'soviet-cartoon';
    case FOREIGN_CARTOON = 'foreign-cartoon';
    case RUSSIAN_CARTOON = 'russian-cartoon';
    case ANIME = 'anime';
    case RUSSIAN_MOVIE = 'russian-movie';
    case CARTOON_SERIAL = 'cartoon-serial';
    case DOCUMENTARY_SERIAL = 'documentary-serial';
    case RUSSIAN_SERIAL = 'russian-serial';
    case FOREIGN_SERIAL = 'foreign-serial';
    case ANIME_SERIAL = 'anime-serial';
    case MULTI_PART_FILM = 'multi-part-film';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @param array<self> $types
     */
    public static function commaSeparated(array $types): string
    {
        return implode(',', array_column($types, 'value'));
    }
}
