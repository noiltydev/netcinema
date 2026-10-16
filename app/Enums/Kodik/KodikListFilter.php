<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

use App\Enums\Contracts\KodikFilterKey;

/**
 * Filters specific to the /list endpoint only.
 */
enum KodikListFilter: string implements KodikFilterKey
{
    case SORT = 'sort';
    case ORDER = 'order';

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
