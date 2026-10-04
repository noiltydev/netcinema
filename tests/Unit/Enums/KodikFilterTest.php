<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikFilter;
use PHPUnit\Framework\TestCase;

class KodikFilterTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        $this->assertSame('types', KodikFilter::Types->value);
        $this->assertSame('year', KodikFilter::Year->value);
        $this->assertSame('translation_type', KodikFilter::TranslationType->value);
        $this->assertSame('has_field', KodikFilter::HasField->value);
        $this->assertSame('lgbt', KodikFilter::Lgbt->value);
        $this->assertSame('sort', KodikFilter::Sort->value);
        $this->assertSame('with_material_data', KodikFilter::WithMaterialData->value);
        $this->assertSame('countries', KodikFilter::Countries->value);
        $this->assertSame('genres', KodikFilter::Genres->value);
        $this->assertSame('anime_genres', KodikFilter::AnimeGenres->value);
        $this->assertSame('drama_genres', KodikFilter::DramaGenres->value);
        $this->assertSame('all_genres', KodikFilter::AllGenres->value);
        $this->assertSame('duration', KodikFilter::Duration->value);
        $this->assertSame('kinopoisk_rating', KodikFilter::KinopoiskRating->value);
        $this->assertSame('imdb_rating', KodikFilter::ImdbRating->value);
        $this->assertSame('shikimori_rating', KodikFilter::ShikimoriRating->value);
        $this->assertSame('mydramalist_rating', KodikFilter::MydramalistRating->value);
        $this->assertSame('actors', KodikFilter::Actors->value);
        $this->assertSame('directors', KodikFilter::Directors->value);
        $this->assertSame('producers', KodikFilter::Producers->value);
        $this->assertSame('writers', KodikFilter::Writers->value);
        $this->assertSame('composers', KodikFilter::Composers->value);
        $this->assertSame('editors', KodikFilter::Editors->value);
        $this->assertSame('designers', KodikFilter::Designers->value);
        $this->assertSame('operators', KodikFilter::Operators->value);
        $this->assertSame('rating_mpaa', KodikFilter::RatingMpaa->value);
        $this->assertSame('minimal_age', KodikFilter::MinimalAge->value);
        $this->assertSame('anime_kind', KodikFilter::AnimeKind->value);
        $this->assertSame('mydramalist_tags', KodikFilter::MydramalistTags->value);
        $this->assertSame('anime_status', KodikFilter::AnimeStatus->value);
        $this->assertSame('drama_status', KodikFilter::DramaStatus->value);
        $this->assertSame('all_status', KodikFilter::AllStatus->value);
        $this->assertSame('anime_studios', KodikFilter::AnimeStudios->value);
        $this->assertSame('anime_licensed_by', KodikFilter::AnimeLicensedBy->value);
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
