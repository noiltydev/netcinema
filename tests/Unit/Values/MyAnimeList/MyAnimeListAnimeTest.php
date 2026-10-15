<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Enums\MyAnimeList\MyAnimeListNsfw;
use App\Values\MyAnimeList\MyAnimeListAnime;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeTest extends TestCase
{
    public function test_from_array_maps_the_fields_an_importer_reads(): void
    {
        $anime = MyAnimeListAnime::fromArray([
            'id' => 30_230,
            'title' => 'One Piece',
            'main_picture' => [
                'medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
            ],
            'alternative_titles' => [
                'synonyms' => ['One Piece'],
                'en' => 'One Piece',
                'ja' => 'ワンピース',
            ],
            'start_date' => '1999-10-20',
            'end_date' => null,
            'mean' => 8.73,
            'rank' => 10,
            'popularity' => 1,
            'num_list_users' => 2_500_000,
            'num_scoring_users' => 2_000_000,
            'nsfw' => 'white',
            'genres' => [['id' => 2, 'name' => 'Adventure']],
            'media_type' => 'tv',
            'status' => 'currently_airing',
            'num_episodes' => 1100,
            'start_season' => ['year' => 1999, 'season' => 'fall'],
            'source' => 'manga',
            'average_episode_duration' => 1440,
            'rating' => 'pg_13',
            'studios' => [['id' => 1, 'name' => 'Toei Animation']],
        ]);

        static::assertSame(30_230, $anime->id);
        static::assertSame('One Piece', $anime->title);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245l.jpg', $anime->mainPictureLarge);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245.jpg', $anime->mainPictureMedium);
        static::assertSame(['One Piece'], $anime->synonyms);
        static::assertSame('One Piece', $anime->titleEnglish);
        static::assertSame('ワンピース', $anime->titleJapanese);
        static::assertSame('1999-10-20', $anime->startedAt?->toDateString());
        static::assertNull($anime->endedAt);
        static::assertSame(8.73, $anime->mean);
        static::assertSame(10, $anime->rank);
        static::assertSame(1, $anime->popularity);
        static::assertSame(2_500_000, $anime->numListUsers);
        static::assertSame(MyAnimeListNsfw::SAFE, $anime->nsfw);
        static::assertCount(1, $anime->genres);
        static::assertSame(2, $anime->genres[0]->id);
        static::assertSame('Adventure', $anime->genres[0]->name);
        static::assertSame(AnimeKind::TV, $anime->kind);
        static::assertSame(EntryStatus::ONGOING, $anime->status);
        static::assertSame(1100, $anime->numEpisodes);
        static::assertSame(1999, $anime->startSeasonYear);
        static::assertSame(EntrySeason::FALL, $anime->startSeason);
        static::assertSame('manga', $anime->source);
        static::assertSame(1440, $anime->averageEpisodeDuration);
        static::assertSame(EntryRating::PG_13, $anime->rating);
    }

    public function test_from_array_handles_the_default_field_set_my_anime_list_returns(): void
    {
        $anime = MyAnimeListAnime::fromArray([
            'id' => 5114,
            'title' => 'Fullmetal Alchemist: Brotherhood',
            'main_picture' => ['medium' => 'https://cdn.myanimelist.net/images/anime/1200/94745.webp'],
        ]);

        static::assertSame(5114, $anime->id);
        static::assertSame('Fullmetal Alchemist: Brotherhood', $anime->title);
        static::assertNull($anime->mainPictureLarge);
        static::assertSame('https://cdn.myanimelist.net/images/anime/1200/94745.webp', $anime->mainPictureMedium);
        static::assertSame([], $anime->synonyms);
        static::assertSame([], $anime->genres);
        static::assertSame(0, $anime->numEpisodes);
        static::assertSame(0, $anime->numListUsers);
        static::assertNull($anime->startedAt);
        static::assertNull($anime->kind);
        static::assertNull($anime->status);
        static::assertNull($anime->rating);
        static::assertNull($anime->nsfw);
    }

    public function test_from_array_reads_dates_truncated_to_a_year_or_month(): void
    {
        $anime = MyAnimeListAnime::fromArray(['start_date' => '1999', 'end_date' => '2005-07']);

        static::assertSame('1999-01-01', $anime->startedAt?->toDateString());
        static::assertSame('00:00:00', $anime->startedAt->toTimeString());
        static::assertSame('2005-07-01', $anime->endedAt?->toDateString());
    }

    public function test_from_array_does_not_read_a_bare_year_as_today(): void
    {
        $anime = MyAnimeListAnime::fromArray(['start_date' => '1999']);

        static::assertNotSame(CarbonImmutable::now()->toDateString(), $anime->startedAt?->toDateString());
        static::assertSame('1999-01-01', $anime->startedAt?->toDateString());
    }

    public function test_from_array_rejects_a_date_it_cannot_read_rather_than_guessing(): void
    {
        $anime = MyAnimeListAnime::fromArray([
            'start_date' => 'not-a-date',
            'end_date' => '',
            'synopsis' => null,
        ]);

        static::assertNull($anime->startedAt);
        static::assertNull($anime->endedAt);
    }

    /**
     * MyAnimeList declares no enum for these fields and its responses already carry values
     * missing from its documentation, so an unknown one has to degrade to null.
     *
     * source is absent on purpose: it stays a raw string so an undocumented value survives.
     */
    #[DataProvider('undocumentedValues')]
    public function test_from_array_degrades_an_undocumented_value_to_null(array $payload, string $property): void
    {
        $anime = MyAnimeListAnime::fromArray($payload);

        static::assertNull($anime->{$property});
    }

    /**
     * @return array<string, array{0: array<string, mixed>, 1: string}>
     */
    public static function undocumentedValues(): array
    {
        return [
            'media_type' => [['media_type' => 'mixed_media'], 'kind'],
            'status' => [['status' => 'on_hiatus'], 'status'],
            'rating' => [['rating' => 'r17'], 'rating'],
            'nsfw' => [['nsfw' => 'purple'], 'nsfw'],
            'start_season' => [['start_season' => ['year' => 1999, 'season' => 'monsoon']], 'startSeason'],
        ];
    }

    public function test_from_array_keeps_an_undocumented_source_rather_than_discarding_it(): void
    {
        $anime = MyAnimeListAnime::fromArray(['source' => 'web_novel']);

        static::assertSame('web_novel', $anime->source);
    }

    public function test_from_array_maps_every_documented_rating_including_the_plus_notation(): void
    {
        $ratings = [
            'g' => EntryRating::G,
            'pg' => EntryRating::PG,
            'pg_13' => EntryRating::PG_13,
            'r' => EntryRating::R,
            'r+' => EntryRating::R_PLUS,
            'rx' => EntryRating::RX,
        ];

        foreach ($ratings as $value => $expected) {
            static::assertSame($expected, MyAnimeListAnime::fromArray(['rating' => $value])->rating);
        }
    }

    public function test_from_array_maps_every_documented_airing_status(): void
    {
        $statuses = [
            'finished_airing' => EntryStatus::RELEASED,
            'currently_airing' => EntryStatus::ONGOING,
            'not_yet_aired' => EntryStatus::ANONS,
        ];

        foreach ($statuses as $value => $expected) {
            static::assertSame($expected, MyAnimeListAnime::fromArray(['status' => $value])->status);
        }
    }

    public function test_from_array_ignores_a_node_of_an_unexpected_shape(): void
    {
        $anime = MyAnimeListAnime::fromArray([
            'id' => 1,
            'title' => 'Broken',
            'main_picture' => 'https://cdn.myanimelist.net/images/anime/1/1.jpg',
            'alternative_titles' => null,
            'genres' => 'not-an-array',
            'genres_with_holes' => [null, ['id' => 2, 'name' => 'Adventure']],
            'start_season' => 'not-an-array',
        ]);

        static::assertSame(1, $anime->id);
        static::assertNull($anime->mainPictureMedium);
        static::assertSame([], $anime->synonyms);
        static::assertSame([], $anime->genres);
        static::assertNull($anime->startSeason);
        static::assertNull($anime->startSeasonYear);
    }

    /**
     * toArray emits the project's vocabulary, not MyAnimeList's, so its output is not
     * readable back by fromArray — that asymmetry is the point of the adapter.
     */
    public function test_to_array_emits_the_projects_vocabulary(): void
    {
        $anime = MyAnimeListAnime::fromArray([
            'id' => 61_316,
            'title' => 'Re:Zero kara Hajimeru Isekai Seikatsu 4th Season',
            'main_picture' => ['medium' => 'm.jpg', 'large' => 'l.jpg'],
            'alternative_titles' => ['synonyms' => ['Re:Zero'], 'en' => 'Re:Zero', 'ja' => 'Re:ゼロ'],
            'start_date' => '2025-09-26',
            'end_date' => '2026-06-28',
            'mean' => 9.01,
            'rank' => 5,
            'popularity' => 7,
            'num_list_users' => 178_348,
            'nsfw' => 'gray',
            'genres' => [['id' => 1, 'name' => 'Action']],
            'media_type' => 'tv',
            'status' => 'currently_airing',
            'num_episodes' => 10,
            'start_season' => ['year' => 2026, 'season' => 'summer'],
            'source' => 'manga',
            'average_episode_duration' => 1440,
            'rating' => 'r+',
        ]);

        static::assertSame(
            [
                'id' => 61_316,
                'title' => 'Re:Zero kara Hajimeru Isekai Seikatsu 4th Season',
                'main_picture' => ['large' => 'l.jpg', 'medium' => 'm.jpg'],
                'alternative_titles' => ['synonyms' => ['Re:Zero'], 'en' => 'Re:Zero', 'ja' => 'Re:ゼロ'],
                'start_date' => '2025-09-26',
                'end_date' => '2026-06-28',
                'mean' => 9.01,
                'rank' => 5,
                'popularity' => 7,
                'num_list_users' => 178_348,
                'nsfw' => 'gray',
                'genres' => [['id' => 1, 'name' => 'Action']],
                'media_type' => 'tv',
                'status' => 'ongoing',
                'num_episodes' => 10,
                'start_season' => ['year' => 2026, 'season' => 'summer'],
                'source' => 'manga',
                'average_episode_duration' => 1440,
                'rating' => 'r_plus',
            ],
            $anime->toArray(),
        );
    }
}
