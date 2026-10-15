<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListRankingType;
use PHPUnit\Framework\TestCase;

class MyAnimeListRankingTypeTest extends TestCase
{
    public function test_the_values_are_the_ones_the_endpoint_documents(): void
    {
        static::assertSame('all', MyAnimeListRankingType::ALL->value);
        static::assertSame('airing', MyAnimeListRankingType::AIRING->value);
        static::assertSame('upcoming', MyAnimeListRankingType::UPCOMING->value);
        static::assertSame('tv', MyAnimeListRankingType::TV->value);
        static::assertSame('ova', MyAnimeListRankingType::OVA->value);
        static::assertSame('movie', MyAnimeListRankingType::MOVIE->value);
        static::assertSame('special', MyAnimeListRankingType::SPECIAL->value);
        static::assertSame('bypopularity', MyAnimeListRankingType::BY_POPULARITY->value);
        static::assertSame('favorite', MyAnimeListRankingType::FAVORITE->value);
    }

    public function test_values_lists_them_in_the_documented_order(): void
    {
        static::assertSame(
            [
                'all',
                'airing',
                'upcoming',
                'tv',
                'ova',
                'movie',
                'special',
                'bypopularity',
                'favorite',
            ],
            MyAnimeListRankingType::values(),
        );
    }

    public function test_values_covers_every_case(): void
    {
        static::assertCount(count(MyAnimeListRankingType::cases()), MyAnimeListRankingType::values());
    }
}
