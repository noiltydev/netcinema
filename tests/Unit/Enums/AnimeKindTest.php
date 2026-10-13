<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\AnimeKind;
use PHPUnit\Framework\TestCase;

class AnimeKindTest extends TestCase
{
    public function test_enum_cases_match_specification(): void
    {
        static::assertSame('tv', AnimeKind::TV->value);
        static::assertSame('movie', AnimeKind::MOVIE->value);
        static::assertSame('ova', AnimeKind::OVA->value);
        static::assertSame('ona', AnimeKind::ONA->value);
        static::assertSame('special', AnimeKind::SPECIAL->value);
        static::assertSame('tv_special', AnimeKind::TV_SPECIAL->value);
        static::assertSame('music', AnimeKind::MUSIC->value);
        static::assertSame('pv', AnimeKind::PV->value);
        static::assertSame('cm', AnimeKind::CM->value);
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
                'tv_special',
                'music',
                'pv',
                'cm',
            ],
            AnimeKind::values(),
        );
    }

    public function test_enum_has_expected_case_count(): void
    {
        static::assertCount(9, AnimeKind::cases());
    }
}
