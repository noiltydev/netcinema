<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;

/**
 * Filters accepted by /anime/{anime_id}, which answers a single node: it takes the field selection
 * and nothing else, so the pagination keys are named here rather than inherited from the common
 * scope, which every list endpoint also uses.
 *
 * @see \App\Http\Integrations\MyAnimeList\Requests\GetAnimeDetailsRequest
 */
final readonly class MyAnimeListAnimeDetailsFilters extends MyAnimeListCommonFilters
{
    public static function make(): self
    {
        return new self([], [
            MyAnimeListCommonFilter::FIELDS->value,
            MyAnimeListCommonFilter::NSFW->value,
        ]);
    }
}
