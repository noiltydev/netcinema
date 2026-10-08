<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikStatus;
use InvalidArgumentException;

/**
 * Immutable, endpoint-scoped set of Kodik API query filters.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetListRequest
 * @see \App\Http\Integrations\Kodik\Requests\GetTranslationsRequest
 */
final readonly class KodikFilters
{
    /**
     * @param array<string, string|int> $query
     * @param array<string> $allowedKeys
     */
    private function __construct(
        private array $query,
        private array $allowedKeys,
    )
    {
    }

    /**
     * Filters accepted by the /list endpoint.
     */
    public static function forList(): self
    {
        return new self([], array_merge(
            KodikFilter::values(),
            KodikListFilter::values(),
        ));
    }

    /**
     * Filters accepted by the /translations/v2 endpoint.
     */
    public static function forTranslations(): self
    {
        return new self([], KodikFilter::values());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): self
    {
        return $this->with(KodikListFilter::LIMIT, $limit);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withTypes(KodikMaterialType ...$types): self
    {
        return $this->with(
            KodikFilter::TYPES,
            KodikMaterialType::commaSeparated($types),
        );
    }

    /**
     * Restricts results to materials carrying at least one of the given external identifiers.
     *
     * @throws InvalidArgumentException
     */
    public function withField(KodikMaterialField ...$fields): self
    {
        return $this->with(
            KodikFilter::HAS_FIELD,
            self::commaSeparated($fields),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withAnimeKind(KodikAnimeKind ...$kinds): self
    {
        return $this->with(
            KodikFilter::ANIME_KIND,
            self::commaSeparated($kinds),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withAnimeStatus(KodikStatus ...$statuses): self
    {
        return $this->with(
            KodikFilter::ANIME_STATUS,
            self::commaSeparated($statuses),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withDramaStatus(KodikStatus ...$statuses): self
    {
        return $this->with(
            KodikFilter::DRAMA_STATUS,
            self::commaSeparated($statuses),
        );
    }

    /**
     * Filters by the status of any source, as opposed to withAnimeStatus and withDramaStatus
     * which each pin the filter to a single source.
     *
     * @throws InvalidArgumentException
     */
    public function withAnyStatus(KodikStatus ...$statuses): self
    {
        return $this->with(
            KodikFilter::ALL_STATUS,
            self::commaSeparated($statuses),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withRatingMpaa(KodikRatingMpaa ...$ratings): self
    {
        return $this->with(
            KodikFilter::RATING_MPAA,
            self::commaSeparated($ratings),
        );
    }

    /**
     * Escape hatch for filters without a dedicated method. The key is still validated against
     * the endpoint scope, so an unknown or out-of-scope key throws rather than reaching the API.
     *
     * @throws InvalidArgumentException
     */
    public function with(KodikFilter|KodikListFilter $key, string|int $value): self
    {
        throw_unless(
            in_array($key->value, $this->allowedKeys, true),
            InvalidArgumentException::class,
            sprintf('Invalid filter key: %s', $key->value),
        );

        return new self([...$this->query, $key->value => $value], $this->allowedKeys);
    }

    /**
     * @return array<string, string|int>
     */
    public function toQuery(): array
    {
        return $this->query;
    }

    /**
     * @param array<covariant string> $values
     */
    private static function commaSeparated(array $values): string
    {
        return implode(',', array_column($values, 'value'));
    }
}
