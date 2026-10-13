<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikCommonFilter;
use App\Enums\KodikListFilter;
use PHPUnit\Framework\TestCase;

class KodikListFilterTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('limit', KodikListFilter::LIMIT->value);
        static::assertSame('order', KodikListFilter::ORDER->value);
    }

    public function test_values_returns_all_list_only_filter_keys(): void
    {
        static::assertSame(
            [
                'limit',
                'order',
            ],
            KodikListFilter::values(),
        );
    }

    public function test_list_only_filters_do_not_overlap_shared_filters(): void
    {
        static::assertSame([], array_intersect(KodikListFilter::values(), KodikCommonFilter::values()));
    }
}
