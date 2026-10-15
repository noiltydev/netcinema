<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;

/**
 * Filters every MyAnimeList list endpoint accepts, whatever else it adds on top.
 */
enum MyAnimeListCommonFilter: string implements MyAnimeListFilterKey
{
    case LIMIT = 'limit';
    case OFFSET = 'offset';
    /** @see \App\Values\MyAnimeList\MyAnimeListFields */
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
