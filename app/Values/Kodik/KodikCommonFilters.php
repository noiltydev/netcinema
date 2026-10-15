<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Contracts\KodikFilterKey;
use App\Enums\KodikAnimeKind;
use App\Enums\KodikCommonFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikStatus;
use BackedEnum;
use InvalidArgumentException;

/**
 * The filters every Kodik endpoint accepts, held immutably beside the endpoint's own scope.
 *
 * Each endpoint scope is its own subclass so that a named builder only exists where the endpoint
 * understands the filter. with() is the escape hatch for everything else: it accepts any key
 * enum, and put() rejects the ones this endpoint does not accept.
 *
 * @see KodikTranslationsFilters
 * @see KodikListFilters
 * @see KodikSearchFilters
 *
 * @phpstan-consistent-constructor
 */
abstract readonly class KodikCommonFilters
{
    /**
     * @param array<string, string|int> $query
     * @param array<string> $allowedKeys
     */
    protected function __construct(
        private array $query,
        private array $allowedKeys,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function withTypes(KodikMaterialType ...$types): static
    {
        return $this->put(KodikCommonFilter::TYPES, KodikMaterialType::commaSeparated($types));
    }

    /**
     * Restricts results to materials carrying at least one of the given external identifiers.
     *
     * @throws InvalidArgumentException
     */
    public function withField(KodikMaterialField ...$fields): static
    {
        return $this->put(KodikCommonFilter::HAS_FIELD, self::commaSeparated($fields));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withAnimeKind(KodikAnimeKind ...$kinds): static
    {
        return $this->put(KodikCommonFilter::ANIME_KIND, self::commaSeparated($kinds));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withAnimeStatus(KodikStatus ...$statuses): static
    {
        return $this->put(KodikCommonFilter::ANIME_STATUS, self::commaSeparated($statuses));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withDramaStatus(KodikStatus ...$statuses): static
    {
        return $this->put(KodikCommonFilter::DRAMA_STATUS, self::commaSeparated($statuses));
    }

    /**
     * Filters by the status of any source, as opposed to withAnimeStatus and withDramaStatus
     * which each pin the filter to a single source.
     *
     * @throws InvalidArgumentException
     */
    public function withAnyStatus(KodikStatus ...$statuses): static
    {
        return $this->put(KodikCommonFilter::ALL_STATUS, self::commaSeparated($statuses));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withRatingMpaa(KodikRatingMpaa ...$ratings): static
    {
        return $this->put(KodikCommonFilter::RATING_MPAA, self::commaSeparated($ratings));
    }

    /**
     * @return array<string, string|int>
     */
    public function toQuery(): array
    {
        return $this->query;
    }

    /**
     * Escape hatch for filters without a dedicated method.
     *
     * @throws InvalidArgumentException
     */
    public function with(KodikFilterKey $key, string|int|bool $value): static
    {
        return $this->put($key, $value);
    }

    /**
     * Writes a key the endpoint scope accepts, rejecting anything outside it rather than
     * letting it reach the API.
     *
     * @throws InvalidArgumentException
     */
    protected function put(KodikFilterKey $key, string|int|bool $value): static
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

    /**
     * @param array<array-key, BackedEnum> $values
     */
    private static function commaSeparated(array $values): string
    {
        return implode(',', array_column($values, 'value'));
    }

    private static function normalizeValue(string|int|bool $value): string|int
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
