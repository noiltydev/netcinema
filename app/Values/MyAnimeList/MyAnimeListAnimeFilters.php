<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;

/**
 * Filters accepted by the /v2/anime endpoint.
 *
 * @see \App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest
 */
final readonly class MyAnimeListAnimeFilters extends MyAnimeListCommonFilters
{
    public static function make(): self
    {
        return new self([], MyAnimeListCommonFilter::values());
    }
}
