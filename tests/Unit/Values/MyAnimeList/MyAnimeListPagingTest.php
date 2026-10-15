<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Values\MyAnimeList\MyAnimeListPaging;
use PHPUnit\Framework\TestCase;

class MyAnimeListPagingTest extends TestCase
{
    public function test_from_array_reads_both_links(): void
    {
        $next = 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4&offset=4';
        $paging = MyAnimeListPaging::fromArray(['previous' => null, 'next' => $next]);

        static::assertNull($paging->previous);
        static::assertSame($next, $paging->next);
    }

    public function test_from_array_treats_absent_links_as_null(): void
    {
        $paging = MyAnimeListPaging::fromArray([]);

        static::assertNull($paging->next);
        static::assertNull($paging->previous);
    }

    public function test_last_page_has_no_next_page_and_no_next_offset(): void
    {
        $paging = MyAnimeListPaging::fromArray(['next' => null]);

        static::assertFalse($paging->hasNextPage());
        static::assertNull($paging->nextOffset());
    }

    public function test_empty_next_link_is_treated_as_the_last_page(): void
    {
        static::assertFalse(MyAnimeListPaging::fromArray(['next' => ''])->hasNextPage());
    }

    public function test_next_offset_is_read_from_the_next_link(): void
    {
        $paging = MyAnimeListPaging::fromArray([
            'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4&offset=4',
        ]);

        static::assertTrue($paging->hasNextPage());
        static::assertSame(4, $paging->nextOffset());
    }

    public function test_next_offset_is_null_when_the_next_link_carries_none(): void
    {
        $paging = MyAnimeListPaging::fromArray([
            'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4',
        ]);

        static::assertTrue($paging->hasNextPage());
        static::assertNull($paging->nextOffset());
    }

    public function test_to_array_returns_both_links(): void
    {
        static::assertSame(
            ['next' => 'https://next', 'previous' => 'https://previous'],
            MyAnimeListPaging::make('https://next', 'https://previous')->toArray(),
        );
    }
}
