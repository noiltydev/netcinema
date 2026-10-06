<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikFilter;
use PHPUnit\Framework\TestCase;

class KodikFilterTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        $this->assertSame('types', KodikFilter::TYPES->value);
        $this->assertSame('year', KodikFilter::YEAR->value);
        $this->assertSame('translation_type', KodikFilter::TRANSLATION_TYPE->value);
        $this->assertSame('has_field', KodikFilter::HAS_FIELD->value);
        $this->assertSame('lgbt', KodikFilter::LGBT->value);
        $this->assertSame('sort', KodikFilter::SORT->value);
        $this->assertSame('with_material_data', KodikFilter::WITH_MATERIAL_DATA->value);
        $this->assertSame('countries', KodikFilter::COUNTRIES->value);
        $this->assertSame('genres', KodikFilter::GENRES->value);
        $this->assertSame('anime_genres', KodikFilter::ANIME_GENRES->value);
        $this->assertSame('drama_genres', KodikFilter::DRAMA_GENRES->value);
        $this->assertSame('all_genres', KodikFilter::ALL_GENRES->value);
        $this->assertSame('duration', KodikFilter::DURATION->value);
        $this->assertSame('kinopoisk_rating', KodikFilter::KINOPOISK_RATING->value);
        $this->assertSame('imdb_rating', KodikFilter::IMDB_RATING->value);
        $this->assertSame('shikimori_rating', KodikFilter::SHIKIMORI_RATING->value);
        $this->assertSame('mydramalist_rating', KodikFilter::MYDRAMALIST_RATING->value);
        $this->assertSame('actors', KodikFilter::ACTORS->value);
        $this->assertSame('directors', KodikFilter::DIRECTORS->value);
        $this->assertSame('producers', KodikFilter::PRODUCERS->value);
        $this->assertSame('writers', KodikFilter::WRITERS->value);
        $this->assertSame('composers', KodikFilter::COMPOSERS->value);
        $this->assertSame('editors', KodikFilter::EDITORS->value);
        $this->assertSame('designers', KodikFilter::DESIGNERS->value);
        $this->assertSame('operators', KodikFilter::OPERATORS->value);
        $this->assertSame('rating_mpaa', KodikFilter::RATING_MPAA->value);
        $this->assertSame('minimal_age', KodikFilter::MINIMAL_AGE->value);
        $this->assertSame('anime_kind', KodikFilter::ANIME_KIND->value);
        $this->assertSame('mydramalist_tags', KodikFilter::MYDRAMALIST_TAGS->value);
        $this->assertSame('anime_status', KodikFilter::ANIME_STATUS->value);
        $this->assertSame('drama_status', KodikFilter::DRAMA_STATUS->value);
        $this->assertSame('all_status', KodikFilter::ALL_STATUS->value);
        $this->assertSame('anime_studios', KodikFilter::ANIME_STUDIOS->value);
        $this->assertSame('anime_licensed_by', KodikFilter::ANIME_LICENSED_BY->value);
    }

    public function test_values_returns_all_filter_keys(): void
    {
        $values = KodikFilter::values();

        $this->assertContains('types', $values);
        $this->assertContains('year', $values);
        $this->assertContains('translation_type', $values);
        $this->assertContains('has_field', $values);
        $this->assertContains('lgbt', $values);
        $this->assertContains('sort', $values);
        $this->assertContains('with_material_data', $values);
        $this->assertContains('countries', $values);
        $this->assertContains('genres', $values);
        $this->assertContains('anime_genres', $values);
        $this->assertContains('drama_genres', $values);
        $this->assertContains('all_genres', $values);
        $this->assertContains('duration', $values);
        $this->assertContains('kinopoisk_rating', $values);
        $this->assertContains('imdb_rating', $values);
        $this->assertContains('shikimori_rating', $values);
        $this->assertContains('mydramalist_rating', $values);
        $this->assertContains('actors', $values);
        $this->assertContains('directors', $values);
        $this->assertContains('producers', $values);
        $this->assertContains('writers', $values);
        $this->assertContains('composers', $values);
        $this->assertContains('editors', $values);
        $this->assertContains('designers', $values);
        $this->assertContains('operators', $values);
        $this->assertContains('rating_mpaa', $values);
        $this->assertContains('minimal_age', $values);
        $this->assertContains('anime_kind', $values);
        $this->assertContains('mydramalist_tags', $values);
        $this->assertContains('anime_status', $values);
        $this->assertContains('drama_status', $values);
        $this->assertContains('all_status', $values);
        $this->assertContains('anime_studios', $values);
        $this->assertContains('anime_licensed_by', $values);
        $this->assertCount(34, $values);
    }
}
