<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikSearchFilter;
use InvalidArgumentException;

/**
 * Filters accepted by the /search endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetSearchRequest
 */
final readonly class KodikSearchFilters extends KodikFilters
{
    public static function make(): self
    {
        return new self([], array_merge(KodikFilter::values(), KodikListFilter::values(), KodikSearchFilter::values()));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): static
    {
        return $this->put(KodikListFilter::LIMIT, $limit);
    }

    /**
     * Matched against title, title_orig and other_title at once. Extra words in the title are
     * tolerated unless withStrictMatch or withFullMatch narrows it down.
     *
     * @throws InvalidArgumentException
     */
    public function withTitle(string $title): static
    {
        return $this->put(KodikSearchFilter::TITLE, $title);
    }

    /**
     * Matched against title_orig alone.
     *
     * @throws InvalidArgumentException
     */
    public function withTitleOrig(string $title): static
    {
        return $this->put(KodikSearchFilter::TITLE_ORIG, $title);
    }

    /**
     * Requires the word order to match the searched title, extra words still allowed.
     *
     * @throws InvalidArgumentException
     */
    public function withStrictMatch(bool $strictMatch = true): static
    {
        return $this->put(KodikSearchFilter::STRICT, $strictMatch);
    }

    /**
     * Requires the title to match completely, apart from letter case.
     *
     * @throws InvalidArgumentException
     */
    public function withFullMatch(bool $fullMatch = true): static
    {
        return $this->put(KodikSearchFilter::FULL_MATCH, $fullMatch);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withKodikId(string $kodikId): static
    {
        return $this->put(KodikSearchFilter::ID, $kodikId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withKinopoiskId(string $kinopoiskId): static
    {
        return $this->put(KodikSearchFilter::KINOPOISK_ID, $kinopoiskId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withImdbId(string $imdbId): static
    {
        return $this->put(KodikSearchFilter::IMDB_ID, $imdbId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withShikimoriId(string $shikimoriId): static
    {
        return $this->put(KodikSearchFilter::SHIKIMORI_ID, $shikimoriId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withSeason(int $season): static
    {
        return $this->put(KodikSearchFilter::SEASON, $season);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withEpisode(int $episode): static
    {
        return $this->put(KodikSearchFilter::EPISODE, $episode);
    }

    /**
     * Escape hatch for filters without a dedicated method.
     *
     * @throws InvalidArgumentException
     */
    public function with(KodikFilter|KodikListFilter|KodikSearchFilter $key, string|int|bool $value): static
    {
        return $this->put($key, $value);
    }
}
