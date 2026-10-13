<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Enums\SourceProviderName;
use App\Models\Anime;
use App\Models\Source;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_anime_can_be_created_with_all_fields(): void
    {
        $anime = Anime::factory()->createOne([
            'kind' => AnimeKind::TV,
            'rating' => EntryRating::PG_13,
            'status' => EntryStatus::ONGOING,
            'title' => 'Test Anime',
            'slug' => 'test-anime',
            'aired_year' => 2024,
            'aired_season' => EntrySeason::WINTER,
            'episodes_count' => 12,
            'duration' => 24,
        ]);

        static::assertSame(AnimeKind::TV, $anime->kind);
        static::assertSame(EntryRating::PG_13, $anime->rating);
        static::assertSame(EntryStatus::ONGOING, $anime->status);
        static::assertSame(EntrySeason::WINTER, $anime->aired_season);
        static::assertSame(2024, $anime->aired_year);
        static::assertSame(12, $anime->episodes_count);
    }

    public function test_sources_relationship_works(): void
    {
        $anime = Anime::factory()->createOne();
        $source = Source::factory()->for($anime, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '123',
        ]);

        static::assertCount(1, $anime->sources);
        static::assertTrue($anime->sources->first()->is($source));
    }

    public function test_unique_index_on_id_and_slug(): void
    {
        Anime::factory()->createOne(['slug' => 'duplicate']);
        $this->expectException(QueryException::class);

        Anime::factory()->createOne(['slug' => 'duplicate']);
    }

    public function test_composite_index_on_aired_year_and_season(): void
    {
        Anime::factory()
            ->count(3)
            ->create([
                'aired_year' => 2024,
                'aired_season' => EntrySeason::WINTER,
            ]);
        Anime::factory()->createOne([
            'aired_year' => 2024,
            'aired_season' => EntrySeason::SPRING,
        ]);

        $winter = Anime::query()->where('aired_year', 2024)->where('aired_season', EntrySeason::WINTER)->get();
        static::assertCount(3, $winter);
    }

    public function test_aired_season_is_cast_to_entry_season_enum(): void
    {
        $anime = Anime::factory()->createOne(['aired_season' => EntrySeason::FALL]);

        static::assertInstanceOf(EntrySeason::class, $anime->aired_season);
        static::assertSame(EntrySeason::FALL, $anime->aired_season);
        static::assertSame('fall', $anime->getRawOriginal('aired_season'));
    }
}
