<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Enums\Contracts\MyAnimeListFilterKey;
use App\Enums\MyAnimeList\MyAnimeListRankingFilter;
use App\Values\MyAnimeList\MyAnimeListRankingFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListRankingFiltersTest extends TestCase
{
    public function test_make_starts_with_an_empty_query_so_no_fields_are_assumed(): void
    {
        static::assertSame([], MyAnimeListRankingFilters::make()->toQuery());
    }

    public function test_with_limit_writes_the_limit(): void
    {
        $filters = MyAnimeListRankingFilters::make()->withLimit(500);

        static::assertSame(['limit' => 500], $filters->toQuery());
    }

    public function test_with_fields_joins_the_fields_the_endpoint_expects(): void
    {
        $filters = MyAnimeListRankingFilters::make()->withFields('id', 'title', 'media_type', 'status', 'rating');

        static::assertSame(['fields' => 'id,title,media_type,status,rating'], $filters->toQuery());
    }

    public function test_with_nsfw_serialises_a_boolean_the_way_my_anime_list_expects(): void
    {
        static::assertSame(['nsfw' => 'true'], MyAnimeListRankingFilters::make()->withNsfw()->toQuery());
        static::assertSame(['nsfw' => 'false'], MyAnimeListRankingFilters::make()->withNsfw(false)->toQuery());
    }

    public function test_nsfw_is_left_out_entirely_unless_it_is_asked_for(): void
    {
        static::assertArrayNotHasKey('nsfw', MyAnimeListRankingFilters::make()->toQuery());
    }

    public function test_filters_accumulate_without_losing_each_other(): void
    {
        $filters = MyAnimeListRankingFilters::make()->withLimit(100)->withFields('id', 'title')->withNsfw();

        static::assertSame(['limit' => 100, 'fields' => 'id,title', 'nsfw' => 'true'], $filters->toQuery());
    }

    public function test_with_rejects_a_key_this_endpoint_does_not_accept(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: ranking_type');

        MyAnimeListRankingFilters::make()
            ->with(MyAnimeListRankingFilter::LIMIT, 100)
            ->with($this->unrelatedFilter(), 'value');
    }

    public function test_to_query_does_not_expose_the_internals(): void
    {
        $filters = MyAnimeListRankingFilters::make()->withLimit(100);

        static::assertSame(['limit' => 100], $filters->toQuery());
    }

    private function unrelatedFilter(): MyAnimeListFilterKey
    {
        return new class implements MyAnimeListFilterKey {
            public function queryKey(): string
            {
                return 'ranking_type';
            }
        };
    }
}
