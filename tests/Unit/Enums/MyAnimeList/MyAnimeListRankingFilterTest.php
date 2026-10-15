<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListRankingFilter;
use PHPUnit\Framework\TestCase;

class MyAnimeListRankingFilterTest extends TestCase
{
    public function test_the_query_keys_are_the_ones_the_endpoint_documents(): void
    {
        static::assertSame('limit', MyAnimeListRankingFilter::LIMIT->queryKey());
        static::assertSame('fields', MyAnimeListRankingFilter::FIELDS->queryKey());
        static::assertSame('nsfw', MyAnimeListRankingFilter::NSFW->queryKey());
    }

    public function test_offset_is_not_a_filter_because_it_carries_pagination_state(): void
    {
        static::assertNotContains('offset', MyAnimeListRankingFilter::values());
    }

    public function test_values_covers_every_case(): void
    {
        static::assertCount(count(MyAnimeListRankingFilter::cases()), MyAnimeListRankingFilter::values());
    }
}
