<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;
use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Values\Concerns\CommonQueryFilters;
use InvalidArgumentException;

/**
 * Filters every MyAnimeList list and details endpoint accepts.
 *
 * Each endpoint scope is its own subclass so that a named builder only exists where the endpoint
 * understands the filter, which is also what makes with()'s rejection meaningful. with() is the
 * escape hatch for everything else: it accepts any key enum, and put() rejects the ones this
 * endpoint does not accept.
 *
 * @see MyAnimeListAnimeRankingFilters
 * @see MyAnimeListAnimeFilters
 *
 * @phpstan-consistent-constructor
 */
abstract readonly class MyAnimeListCommonFilters
{
    use CommonQueryFilters;

    /**
     * @throws InvalidArgumentException
     */
    public function withOffset(int $offset): static
    {
        throw_if(
            $offset < 0,
            InvalidArgumentException::class,
            sprintf('Offset must not be negative, got %d.', $offset),
        );

        return $this->put(MyAnimeListCommonFilter::OFFSET, $offset);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withFields(MyAnimeListFields $fields): static
    {
        return $this->put(MyAnimeListCommonFilter::FIELDS, $fields->toQueryValue());
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
