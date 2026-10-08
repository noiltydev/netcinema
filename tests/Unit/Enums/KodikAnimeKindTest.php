<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikAnimeKind;
use PHPUnit\Framework\TestCase;

class KodikAnimeKindTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('tv', KodikAnimeKind::TV->value);
        static::assertSame('movie', KodikAnimeKind::MOVIE->value);
        static::assertSame('ova', KodikAnimeKind::OVA->value);
        static::assertSame('ona', KodikAnimeKind::ONA->value);
        static::assertSame('special', KodikAnimeKind::SPECIAL->value);
        static::assertSame('music', KodikAnimeKind::MUSIC->value);
        static::assertSame('tv_13', KodikAnimeKind::TV_13->value);
        static::assertSame('tv_24', KodikAnimeKind::TV_24->value);
        static::assertSame('tv_48', KodikAnimeKind::TV_48->value);
    }

    public function test_values_returns_every_anime_kind(): void
    {
        static::assertSame(
            [
                'tv',
                'movie',
                'ova',
                'ona',
                'special',
                'music',
                'tv_13',
                'tv_24',
                'tv_48',
            ],
            KodikAnimeKind::values(),
        );
    }
}
