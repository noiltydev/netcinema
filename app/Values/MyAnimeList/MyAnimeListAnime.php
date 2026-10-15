<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Enums\MyAnimeList\MyAnimeListNsfw;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * The anime node the /anime/ranking endpoint returns.
 *
 * Every field here has a consumer: an animes column, or the identity and display data the
 * importer needs to match and present a title. Fields with nowhere to land — synopsis,
 * studios, scoring users, the broadcast slot — are left out deliberately; broadcast would
 * only feed a next-episode-at rule that is not settled yet.
 *
 * Fields whose names are spelled in the project's vocabulary (kind, status, rating,
 * startSeason) come from MyAnimeListAnimeAdapter. Of the rest, a value MyAnimeList
 * documents completely is held as an enum (nsfw) and a value it documents loosely is held
 * as a raw string (source, whose responses already carry values absent from its
 * documentation) — in both cases an unrecognised value becomes null rather than being
 * mistaken for a missing one.
 */
final readonly class MyAnimeListAnime implements Arrayable
{
    /**
     * @param array<int, string> $synonyms
     * @param array<int, MyAnimeListGenre> $genres
     */
    private function __construct(
        public int $id,
        public string $title,
        public ?string $mainPictureLarge,
        public ?string $mainPictureMedium,
        public array $synonyms,
        public ?string $titleEnglish,
        public ?string $titleJapanese,
        public ?CarbonImmutable $startedAt,
        public ?CarbonImmutable $endedAt,
        public ?float $mean,
        public ?int $rank,
        public ?int $popularity,
        public int $numListUsers,
        public ?MyAnimeListNsfw $nsfw,
        public array $genres,
        public ?AnimeKind $kind,
        public ?EntryStatus $status,
        public int $numEpisodes,
        public ?int $startSeasonYear,
        public ?EntrySeason $startSeason,
        public ?string $source,
        public ?int $averageEpisodeDuration,
        public ?EntryRating $rating,
    ) {}

    /**
     * @param array<int, string> $synonyms
     * @param array<int, MyAnimeListGenre> $genres
     */
    public static function make(
        int $id,
        string $title,
        ?string $mainPictureLarge = null,
        ?string $mainPictureMedium = null,
        array $synonyms = [],
        ?string $titleEnglish = null,
        ?string $titleJapanese = null,
        ?CarbonImmutable $startedAt = null,
        ?CarbonImmutable $endedAt = null,
        ?float $mean = null,
        ?int $rank = null,
        ?int $popularity = null,
        int $numListUsers = 0,
        ?MyAnimeListNsfw $nsfw = null,
        array $genres = [],
        ?AnimeKind $kind = null,
        ?EntryStatus $status = null,
        int $numEpisodes = 0,
        ?int $startSeasonYear = null,
        ?EntrySeason $startSeason = null,
        ?string $source = null,
        ?int $averageEpisodeDuration = null,
        ?EntryRating $rating = null,
    ): self {
        return new self(
            id: $id,
            title: $title,
            mainPictureLarge: $mainPictureLarge,
            mainPictureMedium: $mainPictureMedium,
            synonyms: $synonyms,
            titleEnglish: $titleEnglish,
            titleJapanese: $titleJapanese,
            startedAt: $startedAt,
            endedAt: $endedAt,
            mean: $mean,
            rank: $rank,
            popularity: $popularity,
            numListUsers: $numListUsers,
            nsfw: $nsfw,
            genres: $genres,
            kind: $kind,
            status: $status,
            numEpisodes: $numEpisodes,
            startSeasonYear: $startSeasonYear,
            startSeason: $startSeason,
            source: $source,
            averageEpisodeDuration: $averageEpisodeDuration,
            rating: $rating,
        );
    }

    public static function fromArray(array $data): self
    {
        $picture = self::nested($data, 'main_picture');
        $titles = self::nested($data, 'alternative_titles');
        $startSeason = self::nested($data, 'start_season');

        return self::make(
            id: (int) ($data['id'] ?? 0),
            title: (string) ($data['title'] ?? ''),
            mainPictureLarge: self::nullableString($picture['large'] ?? null),
            mainPictureMedium: self::nullableString($picture['medium'] ?? null),
            synonyms: self::stringList($titles['synonyms'] ?? []),
            titleEnglish: self::nullableString($titles['en'] ?? null),
            titleJapanese: self::nullableString($titles['ja'] ?? null),
            startedAt: self::parseDate($data['start_date'] ?? null),
            endedAt: self::parseDate($data['end_date'] ?? null),
            mean: isset($data['mean']) ? (float) $data['mean'] : null,
            rank: self::nullableInt($data['rank'] ?? null),
            popularity: self::nullableInt($data['popularity'] ?? null),
            numListUsers: (int) ($data['num_list_users'] ?? 0),
            nsfw: MyAnimeListNsfw::tryFrom((string) ($data['nsfw'] ?? '')),
            genres: array_map(MyAnimeListGenre::fromArray(...), self::objectList($data['genres'] ?? [])),
            kind: MyAnimeListAnimeAdapter::kind(self::nullableString($data['media_type'] ?? null)),
            status: MyAnimeListAnimeAdapter::status(self::nullableString($data['status'] ?? null)),
            numEpisodes: (int) ($data['num_episodes'] ?? 0),
            startSeasonYear: self::nullableInt($startSeason['year'] ?? null),
            startSeason: MyAnimeListAnimeAdapter::season(self::nullableString($startSeason['season'] ?? null)),
            source: self::nullableString($data['source'] ?? null),
            averageEpisodeDuration: self::nullableInt($data['average_episode_duration'] ?? null),
            rating: MyAnimeListAnimeAdapter::rating(self::nullableString($data['rating'] ?? null)),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'main_picture' => [
                'large' => $this->mainPictureLarge,
                'medium' => $this->mainPictureMedium,
            ],
            'alternative_titles' => [
                'synonyms' => $this->synonyms,
                'en' => $this->titleEnglish,
                'ja' => $this->titleJapanese,
            ],
            'start_date' => $this->startedAt?->toDateString(),
            'end_date' => $this->endedAt?->toDateString(),
            'mean' => $this->mean,
            'rank' => $this->rank,
            'popularity' => $this->popularity,
            'num_list_users' => $this->numListUsers,
            'nsfw' => $this->nsfw?->value,
            'genres' => array_map(static fn (MyAnimeListGenre $genre): array => $genre->toArray(), $this->genres),
            'media_type' => $this->kind?->value,
            'status' => $this->status?->value,
            'num_episodes' => $this->numEpisodes,
            'start_season' => [
                'year' => $this->startSeasonYear,
                'season' => $this->startSeason?->value,
            ],
            'source' => $this->source,
            'average_episode_duration' => $this->averageEpisodeDuration,
            'rating' => $this->rating?->value,
        ];
    }

    private static function nested(array $data, string $key): array
    {
        return is_array($data[$key] ?? null) ? $data[$key] : [];
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null ? null : (string) $value;
    }

    private static function nullableInt(mixed $value): ?int
    {
        return $value === null ? null : (int) $value;
    }

    /**
     * @return array<int, string>
     */
    private static function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_map(static fn (mixed $item): string => (string) $item, $value));
    }

    /**
     * @return array<int, array>
     */
    private static function objectList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, 'is_array'));
    }

    /**
     * MyAnimeList dates may arrive truncated to a year or a year and month, and Carbon
     * reads a bare year as "today" — which would silently date an anime to the day the
     * import happened. Each documented precision therefore gets its own format, and
     * anything else is rejected rather than guessed at.
     */
    private static function parseDate(mixed $value): ?CarbonImmutable
    {
        if (!is_string($value)) {
            return null;
        }

        $format = match (true) {
            (bool) preg_match('/^\d{4}$/', $value) === true => '!Y',
            (bool) preg_match('/^\d{4}-\d{2}$/', $value) === true => '!Y-m',
            (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === true => '!Y-m-d',
            default => null,
        };

        return $format === null ? null : CarbonImmutable::createFromFormat($format, $value);
    }
}
