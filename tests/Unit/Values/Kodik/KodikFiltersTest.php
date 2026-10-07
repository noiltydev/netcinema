<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikStatus;
use App\Values\Kodik\KodikFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class KodikFiltersTest extends TestCase
{
    public function test_empty_builder_produces_an_empty_query(): void
    {
        $this->assertSame([], KodikFilters::forList()->toQuery());
        $this->assertSame([], KodikFilters::forTranslations()->toQuery());
    }

    public function test_with_limit_writes_the_limit_key(): void
    {
        $query = KodikFilters::forList()->withLimit(100)->toQuery();

        $this->assertSame(['limit' => 100], $query);
    }

    public function test_with_types_joins_the_values_with_commas(): void
    {
        $query = KodikFilters::forList()
            ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
            ->toQuery();

        $this->assertSame(['types' => 'anime,anime-serial'], $query);
    }

    public function test_with_field_writes_the_has_field_key(): void
    {
        $query = KodikFilters::forList()
            ->withField(KodikMaterialField::SHIKIMORI_ID, KodikMaterialField::KINOPOISK_ID)
            ->toQuery();

        $this->assertSame(['has_field' => 'shikimori_id,kinopoisk_id'], $query);
    }

    public function test_with_anime_kind_writes_the_anime_kind_key(): void
    {
        $query = KodikFilters::forList()
            ->withAnimeKind(KodikAnimeKind::TV, KodikAnimeKind::TV_24)
            ->toQuery();

        $this->assertSame(['anime_kind' => 'tv,tv_24'], $query);
    }

    public function test_status_filters_write_their_own_keys_from_one_shared_vocabulary(): void
    {
        $this->assertSame(
            ['anime_status' => 'released'],
            KodikFilters::forList()->withAnimeStatus(KodikStatus::RELEASED)->toQuery(),
        );

        $this->assertSame(
            ['drama_status' => 'ongoing'],
            KodikFilters::forList()->withDramaStatus(KodikStatus::ONGOING)->toQuery(),
        );

        $this->assertSame(
            ['all_status' => 'anons,released'],
            KodikFilters::forList()
                ->withAnyStatus(KodikStatus::ANONS, KodikStatus::RELEASED)
                ->toQuery(),
        );
    }

    public function test_with_rating_mpaa_preserves_the_wire_values(): void
    {
        $query = KodikFilters::forList()
            ->withRatingMpaa(KodikRatingMpaa::PG_13, KodikRatingMpaa::R_PLUS, KodikRatingMpaa::RX)
            ->toQuery();

        $this->assertSame(['rating_mpaa' => 'PG-13,R+,Rx'], $query);
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

        $this->assertSame([
            'limit' => 100,
            'types' => 'anime,anime-serial',
            'has_field' => 'shikimori_id',
            'anime_kind' => 'tv,tv_24',
            'anime_status' => 'released',
            'rating_mpaa' => 'PG-13',
        ], $query);
    }

    public function test_builder_is_immutable(): void
    {
        $base = KodikFilters::forList()->withLimit(50);
        $derived = $base->withTypes(KodikMaterialType::ANIME);

        $this->assertSame(['limit' => 50], $base->toQuery());
        $this->assertSame(['limit' => 50, 'types' => 'anime'], $derived->toQuery());
    }

    public function test_escape_hatch_accepts_any_shared_filter(): void
    {
        $query = KodikFilters::forList()->with(KodikFilter::YEAR, '2023')->toQuery();

        $this->assertSame(['year' => '2023'], $query);
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
        $this->assertSame(
            ['sort' => 'updated_at'],
            KodikFilters::forList()->with(KodikFilter::SORT, 'updated_at')->toQuery(),
        );

        $this->assertSame(
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

        $this->assertSame([
            'types' => 'anime',
            'anime_status' => 'released',
        ], $query);
    }
}
