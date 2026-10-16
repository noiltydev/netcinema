<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikAllOfFilter;
use App\Enums\Kodik\KodikCommonFilter;
use PHPUnit\Framework\TestCase;

class KodikAllOfFilterTest extends TestCase
{
    private const string UNDOCUMENTED_FILTER = 'countries_and_more';

    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('has_field_and', KodikAllOfFilter::HAS_FIELD->value);
        static::assertSame('countries_and', KodikAllOfFilter::COUNTRIES->value);
        static::assertSame('genres_and', KodikAllOfFilter::GENRES->value);
        static::assertSame('anime_genres_and', KodikAllOfFilter::ANIME_GENRES->value);
        static::assertSame('drama_genres_and', KodikAllOfFilter::DRAMA_GENRES->value);
        static::assertSame('all_genres_and', KodikAllOfFilter::ALL_GENRES->value);
        static::assertSame('actors_and', KodikAllOfFilter::ACTORS->value);
        static::assertSame('directors_and', KodikAllOfFilter::DIRECTORS->value);
        static::assertSame('producers_and', KodikAllOfFilter::PRODUCERS->value);
        static::assertSame('writers_and', KodikAllOfFilter::WRITERS->value);
        static::assertSame('composers_and', KodikAllOfFilter::COMPOSERS->value);
        static::assertSame('editors_and', KodikAllOfFilter::EDITORS->value);
        static::assertSame('designers_and', KodikAllOfFilter::DESIGNERS->value);
        static::assertSame('operators_and', KodikAllOfFilter::OPERATORS->value);
        static::assertSame('mydramalist_tags_and', KodikAllOfFilter::MYDRAMALIST_TAGS->value);
        static::assertSame('anime_studios_and', KodikAllOfFilter::ANIME_STUDIOS->value);
        static::assertSame('anime_licensed_by_and', KodikAllOfFilter::ANIME_LICENSED_BY->value);
    }

    public function test_values_returns_every_all_of_key(): void
    {
        static::assertSame(
            [
                'has_field_and',
                'countries_and',
                'genres_and',
                'anime_genres_and',
                'drama_genres_and',
                'all_genres_and',
                'actors_and',
                'directors_and',
                'producers_and',
                'writers_and',
                'composers_and',
                'editors_and',
                'designers_and',
                'operators_and',
                'mydramalist_tags_and',
                'anime_studios_and',
                'anime_licensed_by_and',
            ],
            KodikAllOfFilter::values(),
        );
    }

    public function test_all_of_keys_do_not_overlap_their_plain_counterparts(): void
    {
        $plainKeys = KodikCommonFilter::values();

        foreach (KodikAllOfFilter::values() as $allOfKey) {
            static::assertNotContains($allOfKey, $plainKeys);
        }
    }

    public function test_undocumented_filter_has_no_case(): void
    {
        static::assertNull(KodikAllOfFilter::tryFrom(self::UNDOCUMENTED_FILTER));
    }
}
