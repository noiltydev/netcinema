<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikListSort;
use PHPUnit\Framework\TestCase;

class KodikListSortTest extends TestCase
{
    public function test_cases_match_documented_vocabulary(): void
    {
        static::assertSame('year', KodikListSort::YEAR->value);
        static::assertSame('created_at', KodikListSort::CREATED_AT->value);
        static::assertSame('updated_at', KodikListSort::UPDATED_AT->value);
        static::assertSame('kinopoisk_rating', KodikListSort::KINOPOISK_RATING->value);
        static::assertSame('imdb_rating', KodikListSort::IMDB_RATING->value);
        static::assertSame('shikimori_rating', KodikListSort::SHIKIMORI_RATING->value);
    }

    public function test_catalog_only_values_have_no_case(): void
    {
        static::assertNull(KodikListSort::tryFrom('title'));
        static::assertNull(KodikListSort::tryFrom('count'));
    }
}
