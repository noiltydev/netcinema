<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListMangaDetailField;
use PHPUnit\Framework\TestCase;

class MyAnimeListMangaDetailFieldTest extends TestCase
{
    public function test_enum_cases_match_the_documented_details_fields(): void
    {
        static::assertSame('pictures', MyAnimeListMangaDetailField::PICTURES->value);
        static::assertSame('background', MyAnimeListMangaDetailField::BACKGROUND->value);
        static::assertSame('related_anime', MyAnimeListMangaDetailField::RELATED_ANIME->value);
        static::assertSame('related_manga', MyAnimeListMangaDetailField::RELATED_MANGA->value);
        static::assertSame('recommendations', MyAnimeListMangaDetailField::RECOMMENDATIONS->value);
        static::assertSame('serialization', MyAnimeListMangaDetailField::SERIALIZATION->value);
    }

    public function test_values_returns_every_details_only_manga_field(): void
    {
        static::assertSame(
            [
                'pictures',
                'background',
                'related_anime',
                'related_manga',
                'recommendations',
                'serialization',
            ],
            MyAnimeListMangaDetailField::values(),
        );
    }

    public function test_the_manga_details_exclude_the_anime_only_statistics(): void
    {
        static::assertNotContains('statistics', MyAnimeListMangaDetailField::values());
    }
}
