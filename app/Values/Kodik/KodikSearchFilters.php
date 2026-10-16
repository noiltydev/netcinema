<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Kodik\KodikAllOfFilter;
use App\Enums\Kodik\KodikCommonFilter;
use App\Enums\Kodik\KodikCommonMaterialFilter;
use App\Enums\Kodik\KodikSearchFilter;
use App\Enums\Kodik\KodikSharedListSearchFilter;
use App\Enums\Kodik\KodikTranslationType;
use App\Values\Kodik\Concerns\HasBlockTranslations;
use App\Values\Kodik\Concerns\HasCamrip;
use App\Values\Kodik\Concerns\HasExternalIds;
use App\Values\Kodik\Concerns\HasLimit;
use App\Values\Kodik\Concerns\HasNotBlocked;
use App\Values\Kodik\Concerns\HasSeasonsEpisodes;
use App\Values\Kodik\Concerns\HasTranslationId;
use InvalidArgumentException;

/**
 * Filters accepted by the /search endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetSearchRequest
 */
final readonly class KodikSearchFilters extends KodikCommonFilters
{
    use HasLimit;
    use HasTranslationId;
    use HasBlockTranslations;
    use HasCamrip;
    use HasSeasonsEpisodes;
    use HasNotBlocked;
    use HasExternalIds;

    public static function make(): self
    {
        return new self(
            [],
            array_merge(
                KodikCommonFilter::values(),
                KodikAllOfFilter::values(),
                KodikCommonMaterialFilter::values(),
                KodikSharedListSearchFilter::values(),
                KodikSearchFilter::values(),
            ),
        );
    }

    public static function maxLimit(): int
    {
        return 100;
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
    public function withPlayerLink(string $playerLink): static
    {
        return $this->put(KodikSearchFilter::PLAYER_LINK, $playerLink);
    }

    /**
     * The API turns on with_seasons by itself when a season is asked for.
     *
     * @throws InvalidArgumentException
     */
    public function withSeason(int $season): static
    {
        return $this->put(KodikSearchFilter::SEASON, $season);
    }

    /**
     * The API only accepts an episode alongside the season it belongs to, so sending one alone
     * would be an error rather than a narrower search.
     *
     * @throws InvalidArgumentException
     */
    public function withEpisode(int $episode): static
    {
        throw_if(
            !array_key_exists(KodikSearchFilter::SEASON->queryKey(), $this->query),
            InvalidArgumentException::class,
            'Episode requires a season, call withSeason() first.',
        );

        return $this->put(KodikSearchFilter::EPISODE, $episode);
    }

    /**
     * Accepts translation ids and the voice/subtitles shorthand, where 0 clears the prioritization
     * the API applies by default.
     *
     * @throws InvalidArgumentException
     */
    public function withPrioritizeTranslations(int|string ...$translations): static
    {
        return $this->put(KodikSearchFilter::PRIORITIZE_TRANSLATIONS, implode(',', $translations));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withUnprioritizeTranslations(int|string ...$translations): static
    {
        return $this->put(KodikSearchFilter::UNPRIORITIZE_TRANSLATIONS, implode(',', $translations));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withPrioritizeTranslationType(KodikTranslationType $type): static
    {
        return $this->put(KodikSearchFilter::PRIORITIZE_TRANSLATION_TYPE, $type->value);
    }
}
