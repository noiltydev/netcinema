<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

use App\Enums\Contracts\KodikFilterKey;

/**
 * Filters shared across material-listing endpoints:
 * /list, /search, /years, /genres, /countries, /qualities/v2
 */
enum KodikCommonMaterialFilter: string implements KodikFilterKey
{
    case TRANSLATION_ID = 'translation_id';
    case BLOCK_TRANSLATIONS = 'block_translations';

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
