<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use PHPUnit\Framework\TestCase;

class KodikListFilterTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('limit', KodikListFilter::LIMIT->value);
        static::assertSame('order', KodikListFilter::ORDER->value);
        static::assertSame('translation_id', KodikListFilter::TRANSLATION_ID->value);
        static::assertSame('block_translations', KodikListFilter::BLOCK_TRANSLATIONS->value);
        static::assertSame('camrip', KodikListFilter::CAMRIP->value);
        static::assertSame('with_seasons', KodikListFilter::WITH_SEASONS->value);
        static::assertSame('with_episodes', KodikListFilter::WITH_EPISODES->value);
        static::assertSame('with_episodes_data', KodikListFilter::WITH_EPISODES_DATA->value);
        static::assertSame('with_page_links', KodikListFilter::WITH_PAGE_LINKS->value);
        static::assertSame('not_blocked_in', KodikListFilter::NOT_BLOCKED_IN->value);
        static::assertSame('not_blocked_for_me', KodikListFilter::NOT_BLOCKED_FOR_ME->value);
    }

    public function test_values_returns_all_list_only_filter_keys(): void
    {
        static::assertSame(
            [
                'limit',
                'order',
                'translation_id',
                'block_translations',
                'camrip',
                'with_seasons',
                'with_episodes',
                'with_episodes_data',
                'with_page_links',
                'not_blocked_in',
                'not_blocked_for_me',
            ],
            KodikListFilter::values(),
        );
    }

    public function test_list_only_filters_do_not_overlap_shared_filters(): void
    {
        static::assertSame([], array_intersect(KodikListFilter::values(), KodikFilter::values()));
    }
}
