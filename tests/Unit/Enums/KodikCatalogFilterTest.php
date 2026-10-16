<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikCatalogFilter;
use App\Enums\Kodik\KodikCatalogSort;
use App\Enums\Kodik\KodikCommonFilter;
use App\Enums\Kodik\KodikListSort;
use PHPUnit\Framework\TestCase;

class KodikCatalogFilterTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('sort', KodikCatalogFilter::SORT->value);
    }

    public function test_values_returns_all_catalog_filter_keys(): void
    {
        static::assertSame(['sort'], KodikCatalogFilter::values());
    }

    public function test_sort_is_not_shared_because_the_two_endpoints_accept_different_values(): void
    {
        static::assertNull(KodikCommonFilter::tryFrom('sort'));
        static::assertSame('count', KodikCatalogSort::COUNT->value);
        static::assertSame('kinopoisk_rating', KodikListSort::KINOPOISK_RATING->value);
    }
}
