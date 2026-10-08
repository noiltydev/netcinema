<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikFilter;
use PHPUnit\Framework\TestCase;

class KodikFilterTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        static::assertSame('types', KodikFilter::TYPES->value);
        static::assertSame('year', KodikFilter::YEAR->value);
        static::assertSame('translation_type', KodikFilter::TRANSLATION_TYPE->value);
        static::assertSame('has_field', KodikFilter::HAS_FIELD->value);
        static::assertSame('lgbt', KodikFilter::LGBT->value);
        static::assertSame('sort', KodikFilter::SORT->value);
        static::assertSame('with_material_data', KodikFilter::WITH_MATERIAL_DATA->value);
        static::assertSame('countries', KodikFilter::COUNTRIES->value);
        static::assertSame('genres', KodikFilter::GENRES->value);
        static::assertSame('anime_genres', KodikFilter::ANIME_GENRES->value);
        static::assertSame('drama_genres', KodikFilter::DRAMA_GENRES->value);
        static::assertSame('all_genres', KodikFilter::ALL_GENRES->value);
        static::assertSame('duration', KodikFilter::DURATION->value);
        static::assertSame('kinopoisk_rating', KodikFilter::KINOPOISK_RATING->value);
        static::assertSame('imdb_rating', KodikFilter::IMDB_RATING->value);
        static::assertSame('shikimori_rating', KodikFilter::SHIKIMORI_RATING->value);
        static::assertSame('mydramalist_rating', KodikFilter::MYDRAMALIST_RATING->value);
        static::assertSame('actors', KodikFilter::ACTORS->value);
        static::assertSame('directors', KodikFilter::DIRECTORS->value);
        static::assertSame('producers', KodikFilter::PRODUCERS->value);
        static::assertSame('writers', KodikFilter::WRITERS->value);
        static::assertSame('composers', KodikFilter::COMPOSERS->value);
        static::assertSame('editors', KodikFilter::EDITORS->value);
        static::assertSame('designers', KodikFilter::DESIGNERS->value);
        static::assertSame('operators', KodikFilter::OPERATORS->value);
        static::assertSame('rating_mpaa', KodikFilter::RATING_MPAA->value);
        static::assertSame('minimal_age', KodikFilter::MINIMAL_AGE->value);
        static::assertSame('anime_kind', KodikFilter::ANIME_KIND->value);
        static::assertSame('mydramalist_tags', KodikFilter::MYDRAMALIST_TAGS->value);
        static::assertSame('anime_status', KodikFilter::ANIME_STATUS->value);
        static::assertSame('drama_status', KodikFilter::DRAMA_STATUS->value);
        static::assertSame('all_status', KodikFilter::ALL_STATUS->value);
        static::assertSame('anime_studios', KodikFilter::ANIME_STUDIOS->value);
        static::assertSame('anime_licensed_by', KodikFilter::ANIME_LICENSED_BY->value);
    }

    public function test_values_returns_all_filter_keys(): void
    {
        $values = KodikFilter::values();

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
