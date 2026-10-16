<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListMediaKind;
use PHPUnit\Framework\TestCase;

class MyAnimeListMediaKindTest extends TestCase
{
    public function test_enum_cases_match_the_documented_media_kinds(): void
    {
        static::assertSame('anime', MyAnimeListMediaKind::ANIME->value);
        static::assertSame('manga', MyAnimeListMediaKind::MANGA->value);
    }

    public function test_values_returns_both_media_kinds(): void
    {
        static::assertSame(['anime', 'manga'], MyAnimeListMediaKind::values());
    }
}
