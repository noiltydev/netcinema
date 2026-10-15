<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListNsfw: string
{
    case SAFE = 'white';
    case MAY_BE_UNSAFE = 'gray';
    case UNSAFE = 'black';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
