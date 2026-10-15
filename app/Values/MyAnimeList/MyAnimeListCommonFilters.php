<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;
use App\Enums\MyAnimeList\MyAnimeListCommonField;
use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Values\Concerns\CommonQueryFilters;
use InvalidArgumentException;

/**
 * Filters accepted by the /anime/ranking endpoint.
 *
 * No field set is seeded: MyAnimeList returns only id, title and main_picture unless fields
 * is sent explicitly, so a caller that forgets it gets a near-empty node rather than a
 * wrong one. Request the fields the importer actually reads.
 *
 * @see \App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest
 *
 * @phpstan-consistent-constructor
 */
abstract readonly class MyAnimeListCommonFilters
{
    use CommonQueryFilters;

    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): static
    {
        return $this->put(MyAnimeListCommonFilter::LIMIT, $limit);
    }

    /**
     * MyAnimeList caps this endpoint's limit at 500; anything above is a 400.
     *
     * @throws InvalidArgumentException
     */
    public function withFields(MyAnimeListCommonField ...$fields): static
    {
        return $this->put(MyAnimeListCommonFilter::FIELDS, self::commaSeparated($fields));
    }

    /**
     * Omitted from the query unless asked for, since MyAnimeList hides adult entries by default.
     *
     * @throws InvalidArgumentException
     */
    public function withNsfw(bool $nsfw = true): static
    {
        return $this->put(MyAnimeListCommonFilter::NSFW, $nsfw);
    }

    /**
     * Escape hatch for filters without a dedicated method.
     *
     * @throws InvalidArgumentException
     */
    public function with(MyAnimeListFilterKey $key, string|int|bool $value): static
    {
        return $this->put($key, $value);
    }

    /**
     * Writes a key the endpoint scope accepts, rejecting anything outside it rather than
     * letting it reach the API.
     *
     * @throws InvalidArgumentException
     */
    protected function put(MyAnimeListFilterKey $key, string|int|bool $value): static
    {
        $keyName = $key->queryKey();

        throw_unless(
            in_array($keyName, $this->allowedKeys, true),
            InvalidArgumentException::class,
            sprintf('Invalid filter key: %s', $keyName),
        );

        $query = [...$this->query, $keyName => self::normalizeValue($value)];

        return new static($query, $this->allowedKeys);
    }
}
