<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Enums\MyAnimeList\MyAnimeListSearchFilter;
use PHPUnit\Framework\TestCase;

class MyAnimeListCommonFilterTest extends TestCase
{
    public function test_enum_cases_match_documented_filter_keys(): void
    {
        static::assertSame('limit', MyAnimeListCommonFilter::LIMIT->value);
        static::assertSame('offset', MyAnimeListCommonFilter::OFFSET->value);
        static::assertSame('fields', MyAnimeListCommonFilter::FIELDS->value);
        static::assertSame('nsfw', MyAnimeListCommonFilter::NSFW->value);
    }

    public function test_values_returns_every_common_filter_key(): void
    {
        static::assertSame(['limit', 'offset', 'fields', 'nsfw'], MyAnimeListCommonFilter::values());
    }

    public function test_the_common_filters_exclude_the_search_query(): void
    {
        static::assertNotContains('q', MyAnimeListCommonFilter::values());
        static::assertSame('q', MyAnimeListSearchFilter::Q->value);
        static::assertSame(['q'], MyAnimeListSearchFilter::values());
    }

    public function test_every_key_reports_its_query_key(): void
    {
        static::assertSame('limit', MyAnimeListCommonFilter::LIMIT->queryKey());
        static::assertSame('offset', MyAnimeListCommonFilter::OFFSET->queryKey());
        static::assertSame('fields', MyAnimeListCommonFilter::FIELDS->queryKey());
        static::assertSame('nsfw', MyAnimeListCommonFilter::NSFW->queryKey());
        static::assertSame('q', MyAnimeListSearchFilter::Q->queryKey());
    }
}
