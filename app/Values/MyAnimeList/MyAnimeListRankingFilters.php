<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;
use App\Enums\MyAnimeList\MyAnimeListRankingFilter;
use InvalidArgumentException;

/**
 * Filters accepted by the /anime/ranking endpoint.
 *
 * No field set is seeded: MyAnimeList returns only id, title and main_picture unless fields
 * is sent explicitly, so a caller that forgets it gets a near-empty node rather than a
 * wrong one. Request the fields the importer actually reads.
 *
 * @see \App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest
 */
final readonly class MyAnimeListRankingFilters
{
    /**
     * @param array<string, string|int> $query
     * @param array<string> $allowedKeys
     */
    private function __construct(
        private array $query,
        private array $allowedKeys,
    ) {}

    public static function make(): self
    {
        return new self([], MyAnimeListRankingFilter::values());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): static
    {
        return $this->put(MyAnimeListRankingFilter::LIMIT, $limit);
    }

    /**
     * MyAnimeList caps this endpoint's limit at 500; anything above is a 400.
     *
     * @throws InvalidArgumentException
     */
    public function withFields(string ...$fields): static
    {
        return $this->put(MyAnimeListRankingFilter::FIELDS, implode(',', $fields));
    }

    /**
     * Omitted from the query unless asked for, since MyAnimeList hides adult entries by default.
     *
     * @throws InvalidArgumentException
     */
    public function withNsfw(bool $nsfw = true): static
    {
        return $this->put(MyAnimeListRankingFilter::NSFW, $nsfw);
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
     * @return array<string, string|int>
     */
    public function toQuery(): array
    {
        return $this->query;
    }

    /**
     * Writes a key this endpoint accepts, rejecting anything outside it rather than letting
     * it reach the API.
     *
     * @throws InvalidArgumentException
     */
    private function put(MyAnimeListFilterKey $key, string|int|bool $value): static
    {
        $keyName = $key->queryKey();

        throw_unless(
            in_array($keyName, $this->allowedKeys, true),
            InvalidArgumentException::class,
            sprintf('Invalid filter key: %s', $keyName),
        );

        $query = [...$this->query, $keyName => self::normalizeValue($value)];

        return new self($query, $this->allowedKeys);
    }

    private static function normalizeValue(string|int|bool $value): string|int
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
