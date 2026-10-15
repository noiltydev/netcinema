<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Values\MyAnimeList\Concerns\HasBoundedLimit;

/**
 * Filters accepted by /anime/ranking, which takes no search query and the widest limit of the
 * anime list endpoints.
 *
 * @see \App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest
 */
final readonly class MyAnimeListAnimeRankingFilters extends MyAnimeListCommonFilters
{
    use HasBoundedLimit;

    public static function make(): self
    {
        return new self([], MyAnimeListCommonFilter::values());
    }

    public static function maxLimit(): int
    {
        return 500;
    }
}
