<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikAllOfFilter;
use App\Enums\Kodik\KodikCommonFilter;
use App\Enums\Kodik\KodikCommonMaterialFilter;
use App\Enums\Kodik\KodikListFilter;
use App\Enums\Kodik\KodikSearchFilter;
use App\Enums\Kodik\KodikSharedListSearchFilter;
use PHPUnit\Framework\TestCase;

class KodikSearchFilterTest extends TestCase
{
    private const string UNDOCUMENTED_FILTER = 'q';

    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('title', KodikSearchFilter::TITLE->value);
        static::assertSame('title_orig', KodikSearchFilter::TITLE_ORIG->value);
        static::assertSame('strict', KodikSearchFilter::STRICT->value);
        static::assertSame('full_match', KodikSearchFilter::FULL_MATCH->value);
        static::assertSame('id', KodikSearchFilter::ID->value);
        static::assertSame('player_link', KodikSearchFilter::PLAYER_LINK->value);
        static::assertSame('kinopoisk_id', KodikSearchFilter::KINOPOISK_ID->value);
        static::assertSame('imdb_id', KodikSearchFilter::IMDB_ID->value);
        static::assertSame('mdl_id', KodikSearchFilter::MDL_ID->value);
        static::assertSame('worldart_animation_id', KodikSearchFilter::WORLDART_ANIMATION_ID->value);
        static::assertSame('worldart_cinema_id', KodikSearchFilter::WORLDART_CINEMA_ID->value);
        static::assertSame('worldart_link', KodikSearchFilter::WORLDART_LINK->value);
        static::assertSame('shikimori_id', KodikSearchFilter::SHIKIMORI_ID->value);
        static::assertSame('season', KodikSearchFilter::SEASON->value);
        static::assertSame('episode', KodikSearchFilter::EPISODE->value);
        static::assertSame('prioritize_translations', KodikSearchFilter::PRIORITIZE_TRANSLATIONS->value);
        static::assertSame('unprioritize_translations', KodikSearchFilter::UNPRIORITIZE_TRANSLATIONS->value);
        static::assertSame('prioritize_translation_type', KodikSearchFilter::PRIORITIZE_TRANSLATION_TYPE->value);
    }

    public function test_values_returns_all_search_only_filter_keys(): void
    {
        static::assertSame(
            [
                'title',
                'title_orig',
                'strict',
                'full_match',
                'id',
                'player_link',
                'kinopoisk_id',
                'imdb_id',
                'mdl_id',
                'worldart_animation_id',
                'worldart_cinema_id',
                'worldart_link',
                'shikimori_id',
                'season',
                'episode',
                'prioritize_translations',
                'unprioritize_translations',
                'prioritize_translation_type',
            ],
            KodikSearchFilter::values(),
        );
    }

    public function test_search_only_filters_do_not_overlap_shared_or_list_filters(): void
    {
        static::assertSame(
            [],
            array_intersect(KodikSearchFilter::values(), array_merge(
                KodikCommonFilter::values(),
                KodikAllOfFilter::values(),
                KodikCommonMaterialFilter::values(),
                KodikSharedListSearchFilter::values(),
                KodikListFilter::values(),
            )),
        );
    }

    public function test_has_field_and_is_common_to_every_endpoint_rather_than_search_only(): void
    {
        static::assertNull(KodikSearchFilter::tryFrom('has_field_and'));
        static::assertSame('has_field_and', KodikAllOfFilter::HAS_FIELD->value);
    }

    public function test_list_only_keys_are_absent(): void
    {
        static::assertNull(KodikSearchFilter::tryFrom('sort'));
        static::assertNull(KodikSearchFilter::tryFrom('order'));
    }

    public function test_undocumented_filter_has_no_case(): void
    {
        static::assertNull(KodikSearchFilter::tryFrom(self::UNDOCUMENTED_FILTER));
    }
}
