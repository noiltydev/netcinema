<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikSearchFilter;
use App\Enums\KodikStatus;
use App\Values\Kodik\KodikFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class KodikFiltersTest extends TestCase
{
    public function test_empty_builder_produces_an_empty_query(): void
    {
        static::assertSame([], KodikFilters::forList()->toQuery());
        static::assertSame([], KodikFilters::forTranslations()->toQuery());
    }

    public function test_with_limit_writes_the_limit_key(): void
    {
        $query = KodikFilters::forList()->withLimit(100)->toQuery();

        static::assertSame(['limit' => 100], $query);
    }

    public function test_with_types_joins_the_values_with_commas(): void
    {
        $query = KodikFilters::forList()
            ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
            ->toQuery();

        static::assertSame(['types' => 'anime,anime-serial'], $query);
    }

    public function test_with_field_writes_the_has_field_key(): void
    {
        $query = KodikFilters::forList()
            ->withField(KodikMaterialField::SHIKIMORI_ID, KodikMaterialField::KINOPOISK_ID)
            ->toQuery();

        static::assertSame(['has_field' => 'shikimori_id,kinopoisk_id'], $query);
    }

    public function test_with_anime_kind_writes_the_anime_kind_key(): void
    {
        $query = KodikFilters::forList()->withAnimeKind(KodikAnimeKind::TV, KodikAnimeKind::TV_24)->toQuery();

        static::assertSame(['anime_kind' => 'tv,tv_24'], $query);
    }

    public function test_status_filters_write_their_own_keys_from_one_shared_vocabulary(): void
    {
        static::assertSame(
            ['anime_status' => 'released'],
            KodikFilters::forList()->withAnimeStatus(KodikStatus::RELEASED)->toQuery(),
        );

        static::assertSame(
            ['drama_status' => 'ongoing'],
            KodikFilters::forList()->withDramaStatus(KodikStatus::ONGOING)->toQuery(),
        );

        static::assertSame(
            ['all_status' => 'anons,released'],
            KodikFilters::forList()->withAnyStatus(KodikStatus::ANONS, KodikStatus::RELEASED)->toQuery(),
        );
    }

    public function test_with_rating_mpaa_preserves_the_wire_values(): void
    {
        $query = KodikFilters::forList()
            ->withRatingMpaa(KodikRatingMpaa::PG_13, KodikRatingMpaa::R_PLUS, KodikRatingMpaa::RX)
            ->toQuery();

        static::assertSame(['rating_mpaa' => 'PG-13,R+,Rx'], $query);
    }

    public function test_full_chain_produces_the_expected_query(): void
    {
        $query = KodikFilters::forList()
            ->withLimit(100)
            ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
            ->withField(KodikMaterialField::SHIKIMORI_ID)
            ->withAnimeKind(KodikAnimeKind::TV, KodikAnimeKind::TV_24)
            ->withAnimeStatus(KodikStatus::RELEASED)
            ->withRatingMpaa(KodikRatingMpaa::PG_13)
            ->toQuery();

        static::assertSame(
            [
                'limit' => 100,
                'types' => 'anime,anime-serial',
                'has_field' => 'shikimori_id',
                'anime_kind' => 'tv,tv_24',
                'anime_status' => 'released',
                'rating_mpaa' => 'PG-13',
            ],
            $query,
        );
    }

    public function test_builder_is_immutable(): void
    {
        $base = KodikFilters::forList()->withLimit(50);
        $derived = $base->withTypes(KodikMaterialType::ANIME);

        static::assertSame(['limit' => 50], $base->toQuery());
        static::assertSame(['limit' => 50, 'types' => 'anime'], $derived->toQuery());
    }

    public function test_escape_hatch_accepts_any_shared_filter(): void
    {
        $query = KodikFilters::forList()->with(KodikFilter::YEAR, '2023')->toQuery();

        static::assertSame(['year' => '2023'], $query);
    }

    public function test_list_only_filter_is_rejected_for_translations(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: limit');

        KodikFilters::forTranslations()->withLimit(100);
    }

    public function test_list_only_filter_is_rejected_for_translations_via_escape_hatch(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: limit');

        KodikFilters::forTranslations()->with(KodikListFilter::LIMIT, 100);
    }

    public function test_shared_filter_is_accepted_for_both_scopes(): void
    {
        static::assertSame(
            ['sort' => 'updated_at'],
            KodikFilters::forList()->with(KodikFilter::SORT, 'updated_at')->toQuery(),
        );

        static::assertSame(
            ['sort' => 'title'],
            KodikFilters::forTranslations()->with(KodikFilter::SORT, 'title')->toQuery(),
        );
    }

    public function test_shared_named_filters_are_available_for_translations(): void
    {
        $query = KodikFilters::forTranslations()
            ->withTypes(KodikMaterialType::ANIME)
            ->withAnimeStatus(KodikStatus::RELEASED)
            ->toQuery();

        static::assertSame(
            [
                'types' => 'anime',
                'anime_status' => 'released',
            ],
            $query,
        );
    }

    public function test_empty_search_builder_produces_an_empty_query(): void
    {
        static::assertSame([], KodikFilters::forSearch()->toQuery());
    }

    public function test_search_only_named_builders_write_their_keys(): void
    {
        static::assertSame(
            [
                'title' => 'Спортлото-82',
                'title_orig' => 'Спортлото-82',
                'strict' => '1',
                'full_match' => '1',
                'id' => 'movie-452654',
                'kinopoisk_id' => '43949',
                'imdb_id' => 'tt0084716',
                'shikimori_id' => '12345',
                'season' => 2,
                'episode' => 5,
            ],
            KodikFilters::forSearch()
                ->withTitle('Спортлото-82')
                ->withTitleOrig('Спортлото-82')
                ->withStrictMatch()
                ->withFullMatch()
                ->withKodikId('movie-452654')
                ->withKinopoiskId('43949')
                ->withImdbId('tt0084716')
                ->withShikimoriId('12345')
                ->withSeason(2)
                ->withEpisode(5)
                ->toQuery(),
        );
    }

    public function test_disabled_boolean_search_builders_send_zero(): void
    {
        static::assertSame(
            ['strict' => '0', 'full_match' => '0'],
            KodikFilters::forSearch()->withStrictMatch(false)->withFullMatch(false)->toQuery(),
        );
    }

    public function test_search_scope_accepts_shared_and_list_filters(): void
    {
        $query = KodikFilters::forSearch()
            ->withLimit(100)
            ->withTypes(KodikMaterialType::ANIME_SERIAL)
            ->with(KodikFilter::SORT, 'updated_at')
            ->with(KodikListFilter::ORDER, 'asc')
            ->with(KodikSearchFilter::PLAYER_LINK, 'https://kodikplayer.com/video/19850')
            ->toQuery();

        static::assertSame(
            [
                'limit' => 100,
                'types' => 'anime-serial',
                'sort' => 'updated_at',
                'order' => 'asc',
                'player_link' => 'https://kodikplayer.com/video/19850',
            ],
            $query,
        );
    }

    public function test_search_only_filter_is_rejected_for_list_scope(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: title');

        KodikFilters::forList()->with(KodikSearchFilter::TITLE, 'Спортлото-82');
    }

    public function test_search_only_filter_is_rejected_for_translations_scope(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: title');

        KodikFilters::forTranslations()->with(KodikSearchFilter::TITLE, 'Спортлото-82');
    }

    public function test_boolean_values_are_normalized_for_every_scope(): void
    {
        static::assertSame(['camrip' => '0'], KodikFilters::forList()->with(KodikListFilter::CAMRIP, false)->toQuery());

        static::assertSame(
            ['strict' => '1'],
            KodikFilters::forSearch()->with(KodikSearchFilter::STRICT, true)->toQuery(),
        );
    }
}
