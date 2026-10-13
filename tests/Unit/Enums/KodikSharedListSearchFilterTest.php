<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikCommonFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikSearchFilter;
use App\Enums\KodikSharedListSearchFilter;
use PHPUnit\Framework\TestCase;

class KodikSharedListSearchFilterTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('camrip', KodikSharedListSearchFilter::CAMRIP->value);
        static::assertSame('with_seasons', KodikSharedListSearchFilter::WITH_SEASONS->value);
        static::assertSame('with_episodes', KodikSharedListSearchFilter::WITH_EPISODES->value);
        static::assertSame('with_episodes_data', KodikSharedListSearchFilter::WITH_EPISODES_DATA->value);
        static::assertSame('with_page_links', KodikSharedListSearchFilter::WITH_PAGE_LINKS->value);
        static::assertSame('not_blocked_in', KodikSharedListSearchFilter::NOT_BLOCKED_IN->value);
        static::assertSame('not_blocked_for_me', KodikSharedListSearchFilter::NOT_BLOCKED_FOR_ME->value);
    }

    public function test_values_returns_all_shared_list_search_filter_keys(): void
    {
        static::assertSame(
            [
                'camrip',
                'with_seasons',
                'with_episodes',
                'with_episodes_data',
                'with_page_links',
                'not_blocked_in',
                'not_blocked_for_me',
            ],
            KodikSharedListSearchFilter::values(),
        );
    }

    public function test_shared_filters_do_not_overlap_universal_or_endpoint_specific(): void
    {
        static::assertSame(
            [],
            array_intersect(KodikSharedListSearchFilter::values(), array_merge(
                KodikCommonFilter::values(),
                KodikListFilter::values(),
                KodikSearchFilter::values(),
            )),
        );
    }
}
