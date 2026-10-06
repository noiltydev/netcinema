<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\SourceProvider;
use PHPUnit\Framework\TestCase;

class SourceProviderTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        $this->assertSame('myanimelist', SourceProvider::MYANIMELIST->value);
        $this->assertSame('shikimori', SourceProvider::SHIKIMORI->value);
        $this->assertSame('kinopoisk', SourceProvider::KINOPOISK->value);
        $this->assertSame('imdb', SourceProvider::IMDB->value);
        $this->assertSame('kodik', SourceProvider::KODIK->value);
    }

    public function test_enum_exposes_every_provider_exactly_once(): void
    {
        $values = array_column(SourceProvider::cases(), 'value');

        $this->assertSame(
            ['myanimelist', 'shikimori', 'kinopoisk', 'imdb', 'kodik'],
            $values,
        );
    }
}
