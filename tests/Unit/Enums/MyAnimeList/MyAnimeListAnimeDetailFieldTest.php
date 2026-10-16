<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeDetailField;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeDetailFieldTest extends TestCase
{
    public function test_enum_cases_match_the_documented_details_fields(): void
    {
        static::assertSame('pictures', MyAnimeListAnimeDetailField::PICTURES->value);
        static::assertSame('background', MyAnimeListAnimeDetailField::BACKGROUND->value);
        static::assertSame('related_anime', MyAnimeListAnimeDetailField::RELATED_ANIME->value);
        static::assertSame('related_manga', MyAnimeListAnimeDetailField::RELATED_MANGA->value);
        static::assertSame('recommendations', MyAnimeListAnimeDetailField::RECOMMENDATIONS->value);
        static::assertSame('statistics', MyAnimeListAnimeDetailField::STATISTICS->value);
    }

    public function test_values_returns_every_details_only_anime_field(): void
    {
        static::assertSame(
            [
                'pictures',
                'background',
                'related_anime',
                'related_manga',
                'recommendations',
                'statistics',
            ],
            MyAnimeListAnimeDetailField::values(),
        );
    }

    public function test_the_anime_details_exclude_the_manga_only_serialization(): void
    {
        static::assertNotContains('serialization', MyAnimeListAnimeDetailField::values());
    }
}
