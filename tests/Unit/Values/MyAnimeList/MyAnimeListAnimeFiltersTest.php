<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Enums\MyAnimeList\MyAnimeListSearchFilter;
use App\Values\MyAnimeList\MyAnimeListAnimeFilters;
use App\Values\MyAnimeList\MyAnimeListFields;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeFiltersTest extends TestCase
{
    public function test_empty_scope_produces_an_empty_query(): void
    {
        static::assertSame([], MyAnimeListAnimeFilters::make()->toQuery());
    }

    public function test_with_limit_writes_the_limit_key(): void
    {
        static::assertSame(['limit' => 100], MyAnimeListAnimeFilters::make()->withLimit(100)->toQuery());
    }

    public function test_limit_is_capped_at_the_documented_maximum(): void
    {
        static::assertSame(100, MyAnimeListAnimeFilters::maxLimit());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100, got 101.');

        MyAnimeListAnimeFilters::make()->withLimit(101);
    }

    public function test_limit_must_not_be_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100, got 0.');

        MyAnimeListAnimeFilters::make()->withLimit(0);
    }

    public function test_with_query_writes_the_search_key(): void
    {
        static::assertSame(['q' => 'one piece'], MyAnimeListAnimeFilters::make()->withQuery('one piece')->toQuery());
    }

    public function test_with_offset_writes_the_offset_key(): void
    {
        static::assertSame(['offset' => 20], MyAnimeListAnimeFilters::make()->withOffset(20)->toQuery());
    }

    public function test_offset_must_not_be_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Offset must not be negative, got -1.');

        MyAnimeListAnimeFilters::make()->withOffset(-1);
    }

    public function test_with_fields_writes_the_serialized_fields(): void
    {
        $filters = MyAnimeListAnimeFilters::make()->withFields(MyAnimeListFields::fromAnimeNode(
            MyAnimeListAnimeNodeField::ID,
            MyAnimeListAnimeNodeField::TITLE,
        ));

        static::assertSame(['fields' => 'id,title'], $filters->toQuery());
    }

    public function test_with_nsfw_serializes_booleans_as_strings(): void
    {
        static::assertSame(['nsfw' => 'true'], MyAnimeListAnimeFilters::make()->withNsfw()->toQuery());
        static::assertSame(['nsfw' => 'false'], MyAnimeListAnimeFilters::make()->withNsfw(false)->toQuery());
    }

    public function test_nsfw_is_omitted_unless_requested(): void
    {
        static::assertArrayNotHasKey('nsfw', MyAnimeListAnimeFilters::make()->toQuery());
    }

    public function test_filters_accumulate(): void
    {
        $query = MyAnimeListAnimeFilters::make()
            ->withQuery('one piece')
            ->withLimit(50)
            ->withOffset(100)
            ->withNsfw()
            ->toQuery();

        static::assertSame(['q' => 'one piece', 'limit' => 50, 'offset' => 100, 'nsfw' => 'true'], $query);
    }

    public function test_filters_stay_immutable(): void
    {
        $filters = MyAnimeListAnimeFilters::make();
        $narrowed = $filters->withLimit(10);

        static::assertNotSame($filters, $narrowed);
        static::assertSame([], $filters->toQuery());
        static::assertSame(['limit' => 10], $narrowed->toQuery());
    }

    public function test_escape_hatch_accepts_the_common_and_search_keys(): void
    {
        $query = MyAnimeListAnimeFilters::make()
            ->with(MyAnimeListCommonFilter::LIMIT, 25)
            ->with(MyAnimeListCommonFilter::NSFW, true)
            ->with(MyAnimeListSearchFilter::Q, 'berserk')
            ->toQuery();

        static::assertSame(['limit' => 25, 'nsfw' => 'true', 'q' => 'berserk'], $query);
    }
}
