<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Material status, shared by the anime_status, drama_status and all_status filters.
 */
enum KodikStatus: string
{
    case ANONS = 'anons';
    case ONGOING = 'ongoing';
    case RELEASED = 'released';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
