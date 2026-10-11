<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\Funteam;
use App\Repositories\FunteamRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FunteamRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_many_returns_a_collection_without_ordering_by_default(): void
    {
        Funteam::factory()->createMany([
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
        ]);

        $funteams = $this->repository()->getMany([1, 2]);

        static::assertCount(2, $funteams);
    }

    public function test_get_many_orders_results_to_match_the_requested_id_sequence(): void
    {
        Funteam::factory()->createMany([
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
            ['name' => 'Third', 'slug' => 'third'],
        ]);

        $funteams = $this->repository()->getMany([3, 1, 2], preserveOrder: true);

        static::assertSame(['Third', 'First', 'Second'], $funteams->pluck('name')->all());
    }

    public function test_get_many_skips_ids_with_no_matching_row_when_ordering(): void
    {
        Funteam::factory()->createMany([
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
        ]);

        $funteams = $this->repository()->getMany([2, 999, 1], preserveOrder: true);

        static::assertSame(['Second', 'First'], $funteams->pluck('name')->all());
    }

    public function test_get_many_by_slugs_returns_every_matching_funteam(): void
    {
        Funteam::factory()->createMany([
            ['name' => 'First', 'slug' => 'first'],
            ['name' => 'Second', 'slug' => 'second'],
            ['name' => 'Unrelated', 'slug' => 'unrelated'],
        ]);

        $funteams = $this->repository()->getManyBySlugs(collect(['first', 'second']));

        static::assertEqualsCanonicalizing(['first', 'second'], $funteams->pluck('slug')->all());
    }

    private function repository(): FunteamRepository
    {
        return $this->app->make(FunteamRepository::class);
    }
}
