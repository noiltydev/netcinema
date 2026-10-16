<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;

/**
 * Filters only the catalogue search endpoints accept; the ranking endpoints have no search query.
 */
enum MyAnimeListSearchFilter: string implements MyAnimeListFilterKey
{
    case Q = 'q';

    public function queryKey(): string
    {
        return $this->value;
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
