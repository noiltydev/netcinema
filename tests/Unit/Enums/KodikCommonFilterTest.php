<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikCommonFilter;
use PHPUnit\Framework\TestCase;

class KodikCommonFilterTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        static::assertSame('types', KodikCommonFilter::TYPES->value);
        static::assertSame('year', KodikCommonFilter::YEAR->value);
        static::assertSame('translation_type', KodikCommonFilter::TRANSLATION_TYPE->value);
        static::assertSame('has_field', KodikCommonFilter::HAS_FIELD->value);
        static::assertSame('lgbt', KodikCommonFilter::LGBT->value);
        static::assertSame('sort', KodikCommonFilter::SORT->value);
        static::assertSame('with_material_data', KodikCommonFilter::WITH_MATERIAL_DATA->value);
        static::assertSame('countries', KodikCommonFilter::COUNTRIES->value);
        static::assertSame('genres', KodikCommonFilter::GENRES->value);
        static::assertSame('anime_genres', KodikCommonFilter::ANIME_GENRES->value);
        static::assertSame('drama_genres', KodikCommonFilter::DRAMA_GENRES->value);
        static::assertSame('all_genres', KodikCommonFilter::ALL_GENRES->value);
        static::assertSame('duration', KodikCommonFilter::DURATION->value);
        static::assertSame('kinopoisk_rating', KodikCommonFilter::KINOPOISK_RATING->value);
        static::assertSame('imdb_rating', KodikCommonFilter::IMDB_RATING->value);
        static::assertSame('shikimori_rating', KodikCommonFilter::SHIKIMORI_RATING->value);
        static::assertSame('mydramalist_rating', KodikCommonFilter::MYDRAMALIST_RATING->value);
        static::assertSame('actors', KodikCommonFilter::ACTORS->value);
        static::assertSame('directors', KodikCommonFilter::DIRECTORS->value);
        static::assertSame('producers', KodikCommonFilter::PRODUCERS->value);
        static::assertSame('writers', KodikCommonFilter::WRITERS->value);
        static::assertSame('composers', KodikCommonFilter::COMPOSERS->value);
        static::assertSame('editors', KodikCommonFilter::EDITORS->value);
        static::assertSame('designers', KodikCommonFilter::DESIGNERS->value);
        static::assertSame('operators', KodikCommonFilter::OPERATORS->value);
        static::assertSame('rating_mpaa', KodikCommonFilter::RATING_MPAA->value);
        static::assertSame('minimal_age', KodikCommonFilter::MINIMAL_AGE->value);
        static::assertSame('anime_kind', KodikCommonFilter::ANIME_KIND->value);
        static::assertSame('mydramalist_tags', KodikCommonFilter::MYDRAMALIST_TAGS->value);
        static::assertSame('anime_status', KodikCommonFilter::ANIME_STATUS->value);
        static::assertSame('drama_status', KodikCommonFilter::DRAMA_STATUS->value);
        static::assertSame('all_status', KodikCommonFilter::ALL_STATUS->value);
        static::assertSame('anime_studios', KodikCommonFilter::ANIME_STUDIOS->value);
        static::assertSame('anime_licensed_by', KodikCommonFilter::ANIME_LICENSED_BY->value);
    }

    public function test_values_returns_all_filter_keys(): void
    {
        $values = KodikCommonFilter::values();

        static::assertContains('types', $values);
        static::assertContains('year', $values);
        static::assertContains('translation_type', $values);
        static::assertContains('has_field', $values);
        static::assertContains('lgbt', $values);
        static::assertContains('sort', $values);
        static::assertContains('with_material_data', $values);
        static::assertContains('countries', $values);
        static::assertContains('genres', $values);
        static::assertContains('anime_genres', $values);
        static::assertContains('drama_genres', $values);
        static::assertContains('all_genres', $values);
        static::assertContains('duration', $values);
        static::assertContains('kinopoisk_rating', $values);
        static::assertContains('imdb_rating', $values);
        static::assertContains('shikimori_rating', $values);
        static::assertContains('mydramalist_rating', $values);
        static::assertContains('actors', $values);
        static::assertContains('directors', $values);
        static::assertContains('producers', $values);
        static::assertContains('writers', $values);
        static::assertContains('composers', $values);
        static::assertContains('editors', $values);
        static::assertContains('designers', $values);
        static::assertContains('operators', $values);
        static::assertContains('rating_mpaa', $values);
        static::assertContains('minimal_age', $values);
        static::assertContains('anime_kind', $values);
        static::assertContains('mydramalist_tags', $values);
        static::assertContains('anime_status', $values);
        static::assertContains('drama_status', $values);
        static::assertContains('all_status', $values);
        static::assertContains('anime_studios', $values);
        static::assertContains('anime_licensed_by', $values);
        static::assertCount(34, $values);
    }
}
