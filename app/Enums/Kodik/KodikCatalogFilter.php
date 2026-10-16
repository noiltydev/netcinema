<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

use App\Enums\Contracts\KodikFilterKey;

/**
 * Filters shared by the endpoints that return a title/count catalogue.
 *
 * @see KodikTranslationsFilters
 */
enum KodikCatalogFilter: string implements KodikFilterKey
{
    case SORT = 'sort';

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
