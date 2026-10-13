<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Anime;
use App\Models\Concerns\MorphsToSources;
use PHPUnit\Framework\TestCase;

class AnimeTest extends TestCase
{
    public function test_fillable_attributes(): void
    {
        static::assertEqualsCanonicalizing(
            [
                'kind',
                'rating',
                'status',
                'title',
                'slug',
                'aired_at',
                'aired_year',
                'aired_season',
                'released_at',
                'episodes_count',
                'episodes_aired',
                'duration',
                'next_episode_at',
            ],
            (new Anime())->getFillable(),
        );
    }

    public function test_uses_morphs_to_sources_trait(): void
    {
        static::assertTrue(in_array(
            MorphsToSources::class,
            class_uses_recursive(Anime::class),
            true,
        ));
    }
}
