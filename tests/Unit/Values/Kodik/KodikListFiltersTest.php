<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikCommonFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikSharedListSearchFilter;
use App\Enums\KodikStatus;
use App\Values\Kodik\KodikListFilters;
use PHPUnit\Framework\TestCase;

class KodikListFiltersTest extends TestCase
{
    public function test_empty_scope_produces_an_empty_query(): void
    {
        static::assertSame([], KodikListFilters::make()->toQuery());
    }

    public function test_with_limit_writes_the_limit_key(): void
    {
        $query = KodikListFilters::make()->withLimit(100)->toQuery();

        static::assertSame(['limit' => 100], $query);
    }

    public function test_escape_hatch_accepts_shared_and_list_filters(): void
    {
        $query = KodikListFilters::make()
            ->with(KodikCommonFilter::TYPES, 'anime-serial')
            ->with(KodikCommonFilter::SORT, 'updated_at')
            ->with(KodikListFilter::LIMIT, 100)
            ->with(KodikListFilter::ORDER, 'asc')
            ->toQuery();

        static::assertSame(
            [
                'types' => 'anime-serial',
                'sort' => 'updated_at',
                'limit' => 100,
                'order' => 'asc',
            ],
            $query,
        );
    }

    public function test_full_chain_produces_the_expected_query(): void
    {
        $query = KodikListFilters::make()
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

    public function test_boolean_shared_filter_is_normalized(): void
    {
        $query = KodikListFilters::make()->with(KodikSharedListSearchFilter::CAMRIP, false)->toQuery();

        static::assertSame(['camrip' => '0'], $query);
    }

    public function test_with_translation_id_joins_the_ids_with_commas(): void
    {
        $query = KodikListFilters::make()->withTranslationId(714, 720)->toQuery();

        static::assertSame(['translation_id' => '714,720'], $query);
    }

    public function test_with_translation_id_is_immutable(): void
    {
        $base = KodikListFilters::make()->withLimit(50);
        $derived = $base->withTranslationId(714);

        static::assertSame(['limit' => 50], $base->toQuery());
        static::assertSame(['limit' => 50, 'translation_id' => '714'], $derived->toQuery());
    }
}
