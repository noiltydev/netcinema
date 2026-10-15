<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Contracts;

use App\Enums\Contracts\MyAnimeListFilterRankingTypeKey;
use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Enums\MyAnimeList\MyAnimeListRankingTypeMangaFilter;
use PHPUnit\Framework\TestCase;

class MyAnimeListFilterRankingTypeKeyTest extends TestCase
{
    public function test_the_ranking_enums_are_ranking_type_keys(): void
    {
        static::assertInstanceOf(MyAnimeListFilterRankingTypeKey::class, MyAnimeListRankingTypeAnimeFilter::ALL);
        static::assertInstanceOf(MyAnimeListFilterRankingTypeKey::class, MyAnimeListRankingTypeMangaFilter::ALL);
    }

    public function test_both_ranking_endpoints_share_the_query_key(): void
    {
        static::assertSame('ranking_type', MyAnimeListRankingTypeAnimeFilter::queryRankingTypeKey());
        static::assertSame('ranking_type', MyAnimeListRankingTypeMangaFilter::queryRankingTypeKey());
    }
}
