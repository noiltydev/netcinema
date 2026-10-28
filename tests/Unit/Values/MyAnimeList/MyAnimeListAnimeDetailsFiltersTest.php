<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeDetailField;
use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Values\MyAnimeList\MyAnimeListAnimeDetailsFilters;
use App\Values\MyAnimeList\MyAnimeListFields;
use App\Values\MyAnimeList\MyAnimeListFieldSelection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeDetailsFiltersTest extends TestCase
{
    public function test_empty_scope_produces_an_empty_query(): void
    {
        static::assertSame([], MyAnimeListAnimeDetailsFilters::make()->toQuery());
    }

    public function test_it_accepts_the_field_selection_and_nsfw(): void
    {
        $query = MyAnimeListAnimeDetailsFilters::make()
            ->withFields(MyAnimeListFields::fromAnimeDetail(MyAnimeListAnimeDetailField::STATISTICS))
            ->withNsfw()
            ->toQuery();

        static::assertSame(['fields' => 'statistics', 'nsfw' => 'true'], $query);
    }

    public function test_it_serializes_a_nested_field_selection(): void
    {
        $filters = MyAnimeListAnimeDetailsFilters::make()->withFields(MyAnimeListFields::fromAnimeDetail(
            MyAnimeListAnimeDetailField::STATISTICS,
        )->withSelection(MyAnimeListFieldSelection::make(
            'statistics',
            'num_list_users',
            MyAnimeListFieldSelection::make('status', 'watching'),
        )));

        static::assertSame('statistics{num_list_users,status{watching}}', $filters->toQuery()['fields']);
    }

    public function test_the_last_field_selection_wins(): void
    {
        $filters = MyAnimeListAnimeDetailsFilters::make()
            ->withFields(MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::ID))
            ->withFields(MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::TITLE));

        static::assertSame(['fields' => 'title'], $filters->toQuery());
    }

    public function test_it_rejects_the_pagination_keys(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: limit');

        MyAnimeListAnimeDetailsFilters::make()->with(MyAnimeListCommonFilter::LIMIT, 100);
    }

    public function test_with_offset_refuses_to_write(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: offset');

        MyAnimeListAnimeDetailsFilters::make()->withOffset(20);
    }

    public function test_filters_stay_immutable(): void
    {
        $filters = MyAnimeListAnimeDetailsFilters::make();
        $narrowed = $filters->withNsfw();

        static::assertNotSame($filters, $narrowed);
        static::assertSame([], $filters->toQuery());
        static::assertSame(['nsfw' => 'true'], $narrowed->toQuery());
    }
}
