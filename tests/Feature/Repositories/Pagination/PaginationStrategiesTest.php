<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories\Pagination;

use App\Models\Funteam;
use App\Repositories\Pagination\CursorStrategy;
use App\Repositories\Pagination\OffsetStrategy;
use App\Repositories\Pagination\PaginationStrategyResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PaginationStrategiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_resolver_picks_offset_strategy_without_cursor(): void
    {
        $strategy = PaginationStrategyResolver::resolve(Request::create('/funteams'));

        $this->assertInstanceOf(OffsetStrategy::class, $strategy);
    }

    public function test_resolver_picks_cursor_strategy_with_cursor(): void
    {
        $strategy = PaginationStrategyResolver::resolve(Request::create('/funteams?cursor=abc'));

        $this->assertInstanceOf(CursorStrategy::class, $strategy);
    }

    public function test_offset_strategy_orders_by_id_column(): void
    {
        Funteam::factory()->createMany([
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
            ['name' => 'Third', 'slug' => 'third'],
        ]);

        $items = (new OffsetStrategy())
            ->apply(Funteam::query(), 'id', 2)
            ->items();

        $this->assertCount(2, $items);
        $this->assertSame('First', $items[0]->name);
        $this->assertSame('Second', $items[1]->name);
    }

    public function test_cursor_strategy_orders_by_id_column(): void
    {
        Funteam::factory()->createMany([
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
        ]);

        $items = (new CursorStrategy(null))
            ->apply(Funteam::query(), 'id', 1)
            ->items();

        $this->assertCount(1, $items);
        $this->assertSame('First', $items[0]->name);
    }
}
