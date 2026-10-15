<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;

enum MyAnimeListCommonFilter: string implements MyAnimeListFilterKey
{
    case LIMIT = 'limit';
    case OFFSET = 'offset';
    /** @see MyAnimeListCommonField */
    case FIELDS = 'fields';
    case NSFW = 'nsfw';

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
