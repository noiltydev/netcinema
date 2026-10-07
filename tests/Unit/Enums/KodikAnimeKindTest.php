<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikAnimeKind;
use PHPUnit\Framework\TestCase;

class KodikAnimeKindTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        $this->assertSame('tv', KodikAnimeKind::TV->value);
        $this->assertSame('movie', KodikAnimeKind::MOVIE->value);
        $this->assertSame('ova', KodikAnimeKind::OVA->value);
        $this->assertSame('ona', KodikAnimeKind::ONA->value);
        $this->assertSame('special', KodikAnimeKind::SPECIAL->value);
        $this->assertSame('music', KodikAnimeKind::MUSIC->value);
        $this->assertSame('tv_13', KodikAnimeKind::TV_13->value);
        $this->assertSame('tv_24', KodikAnimeKind::TV_24->value);
        $this->assertSame('tv_48', KodikAnimeKind::TV_48->value);
    }

    public function test_values_returns_every_anime_kind(): void
    {
        $this->assertSame([
            'tv',
            'movie',
            'ova',
            'ona',
            'special',
            'music',
            'tv_13',
            'tv_24',
            'tv_48',
        ], KodikAnimeKind::values());
    }
}
