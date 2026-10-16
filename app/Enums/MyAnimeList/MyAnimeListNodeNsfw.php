<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

enum MyAnimeListNodeNsfw: string
{
    case WHITE = 'white';
    case GRAY = 'gray';
    case BLACK = 'black';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
