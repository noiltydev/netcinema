<?php

declare(strict_types=1);

namespace Tests\Feature\Factories;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntryStatus;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnimeFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_valid_anime(): void
    {
        $anime = Anime::factory()->createOne();

        static::assertInstanceOf(Anime::class, $anime);
        static::assertContains($anime->kind->value, AnimeKind::values());
        static::assertContains($anime->rating->value, EntryRating::values());
        static::assertContains($anime->status->value, EntryStatus::values());
        static::assertNotEmpty($anime->title);
        static::assertNotEmpty($anime->slug);
        static::assertGreaterThanOrEqual(1990, $anime->aired_year);
        static::assertLessThanOrEqual(2026, $anime->aired_year);
        static::assertGreaterThan(0, $anime->episodes_count);
        static::assertGreaterThan(0, $anime->duration);
    }
}
