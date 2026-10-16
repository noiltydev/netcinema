<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Enums\MyAnimeList\MyAnimeListSearchFilter;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeRankingFiltersTest extends TestCase
{
    public function test_empty_scope_produces_an_empty_query(): void
    {
        static::assertSame([], MyAnimeListAnimeRankingFilters::make()->toQuery());
    }

    public function test_limit_is_capped_at_the_documented_maximum(): void
    {
        static::assertSame(500, MyAnimeListAnimeRankingFilters::maxLimit());
        static::assertSame(['limit' => 500], MyAnimeListAnimeRankingFilters::make()->withLimit(500)->toQuery());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 500, got 501.');

        MyAnimeListAnimeRankingFilters::make()->withLimit(501);
    }

    public function test_the_ranking_endpoint_takes_no_search_query(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: q');

        MyAnimeListAnimeRankingFilters::make()->with(MyAnimeListSearchFilter::Q, 'one piece');
    }

    public function test_the_ranking_endpoint_accepts_the_common_filters(): void
    {
        $query = MyAnimeListAnimeRankingFilters::make()
            ->withLimit(100)
            ->withOffset(200)
            ->withNsfw()
            ->with(MyAnimeListCommonFilter::FIELDS, 'id,title')
            ->toQuery();

        static::assertSame(['limit' => 100, 'offset' => 200, 'nsfw' => 'true', 'fields' => 'id,title'], $query);
    }
}
