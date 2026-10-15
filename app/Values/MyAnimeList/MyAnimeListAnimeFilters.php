<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Enums\MyAnimeList\MyAnimeListSearchFilter;
use App\Values\MyAnimeList\Concerns\HasBoundedLimit;
use InvalidArgumentException;

/**
 * Filters accepted by /anime, which searches the catalogue and therefore caps the limit at a
 * third of what the ranking endpoints accept.
 *
 * @see \App\Http\Integrations\MyAnimeList\Requests\GetAnimeListRequest
 */
final readonly class MyAnimeListAnimeFilters extends MyAnimeListCommonFilters
{
    use HasBoundedLimit;

    public static function make(): self
    {
        return new self([], [...MyAnimeListCommonFilter::values(), ...MyAnimeListSearchFilter::values()]);
    }

    public static function maxLimit(): int
    {
        return 100;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withQuery(string $query): static
    {
        return $this->put(MyAnimeListSearchFilter::Q, $query);
    }
}
