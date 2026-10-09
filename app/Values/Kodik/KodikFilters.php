<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikSearchFilter;
use App\Enums\KodikStatus;
use BackedEnum;
use InvalidArgumentException;

/**
 * Immutable, endpoint-scoped set of Kodik API query filters.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetListRequest
 * @see \App\Http\Integrations\Kodik\Requests\GetSearchRequest
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
    ) {}

    /**
     * Filters accepted by the /list endpoint.
     */
    public static function forList(): self
    {
        return new self([], array_merge(KodikFilter::values(), KodikListFilter::values()));
    }

    /**
     * Filters accepted by the /translations/v2 endpoint.
     */
    public static function forTranslations(): self
    {
        return new self([], KodikFilter::values());
    }

    /**
     * Filters accepted by the /search endpoint.
     */
    public static function forSearch(): self
    {
        return new self([], array_merge(KodikFilter::values(), KodikListFilter::values(), KodikSearchFilter::values()));
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
        return $this->with(KodikFilter::TYPES, KodikMaterialType::commaSeparated($types));
    }

    /**
     * Restricts results to materials carrying at least one of the given external identifiers.
     *
     * @throws InvalidArgumentException
     */
    public function withField(KodikMaterialField ...$fields): self
    {
        return $this->with(KodikFilter::HAS_FIELD, self::commaSeparated($fields));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withAnimeKind(KodikAnimeKind ...$kinds): self
    {
        return $this->with(KodikFilter::ANIME_KIND, self::commaSeparated($kinds));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withAnimeStatus(KodikStatus ...$statuses): self
    {
        return $this->with(KodikFilter::ANIME_STATUS, self::commaSeparated($statuses));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withDramaStatus(KodikStatus ...$statuses): self
    {
        return $this->with(KodikFilter::DRAMA_STATUS, self::commaSeparated($statuses));
    }

    /**
     * Filters by the status of any source, as opposed to withAnimeStatus and withDramaStatus
     * which each pin the filter to a single source.
     *
     * @throws InvalidArgumentException
     */
    public function withAnyStatus(KodikStatus ...$statuses): self
    {
        return $this->with(KodikFilter::ALL_STATUS, self::commaSeparated($statuses));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withRatingMpaa(KodikRatingMpaa ...$ratings): self
    {
        return $this->with(KodikFilter::RATING_MPAA, self::commaSeparated($ratings));
    }

    /**
     * Matched against title, title_orig and other_title at once. Extra words in the title are
     * tolerated unless withStrictMatch or withFullMatch narrows it down.
     *
     * @throws InvalidArgumentException
     */
    public function withTitle(string $title): self
    {
        return $this->with(KodikSearchFilter::TITLE, $title);
    }

    /**
     * Matched against title_orig alone.
     *
     * @throws InvalidArgumentException
     */
    public function withTitleOrig(string $title): self
    {
        return $this->with(KodikSearchFilter::TITLE_ORIG, $title);
    }

    /**
     * Requires the word order to match the searched title, extra words still allowed.
     *
     * @throws InvalidArgumentException
     */
    public function withStrictMatch(bool $strictMatch = true): self
    {
        return $this->with(KodikSearchFilter::STRICT, $strictMatch);
    }

    /**
     * Requires the title to match completely, apart from letter case.
     *
     * @throws InvalidArgumentException
     */
    public function withFullMatch(bool $fullMatch = true): self
    {
        return $this->with(KodikSearchFilter::FULL_MATCH, $fullMatch);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withKodikId(string $kodikId): self
    {
        return $this->with(KodikSearchFilter::ID, $kodikId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withKinopoiskId(string $kinopoiskId): self
    {
        return $this->with(KodikSearchFilter::KINOPOISK_ID, $kinopoiskId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withImdbId(string $imdbId): self
    {
        return $this->with(KodikSearchFilter::IMDB_ID, $imdbId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withShikimoriId(string $shikimoriId): self
    {
        return $this->with(KodikSearchFilter::SHIKIMORI_ID, $shikimoriId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withSeason(int $season): self
    {
        return $this->with(KodikSearchFilter::SEASON, $season);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withEpisode(int $episode): self
    {
        return $this->with(KodikSearchFilter::EPISODE, $episode);
    }

    /**
     * Escape hatch for filters without a dedicated method. The key is still validated against
     * the endpoint scope, so an unknown or out-of-scope key throws rather than reaching the API.
     *
     * @throws InvalidArgumentException
     */
    public function with(KodikFilter|KodikListFilter|KodikSearchFilter $key, string|int|bool $value): self
    {
        throw_unless(
            in_array($key->value, $this->allowedKeys, true),
            InvalidArgumentException::class,
            sprintf('Invalid filter key: %s', $key->value),
        );

        $query = [...$this->query, $key->value => self::normalizeValue($value)];

        return new self($query, $this->allowedKeys);
    }

    /**
     * @return array<string, string|int>
     */
    public function toQuery(): array
    {
        return $this->query;
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
            return $value ? '1' : '0';
        }

        return $value;
    }
}
