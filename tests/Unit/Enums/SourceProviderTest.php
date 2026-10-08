<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\SourceProviderName;
use PHPUnit\Framework\TestCase;

class SourceProviderTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        static::assertSame('myanimelist', SourceProviderName::MYANIMELIST->value);
        static::assertSame('shikimori', SourceProviderName::SHIKIMORI->value);
        static::assertSame('kinopoisk', SourceProviderName::KINOPOISK->value);
        static::assertSame('imdb', SourceProviderName::IMDB->value);
        static::assertSame('kodik', SourceProviderName::KODIK->value);
    }

    public function test_enum_exposes_every_provider_exactly_once(): void
    {
        $values = array_column(SourceProviderName::cases(), 'value');

        static::assertSame(['myanimelist', 'shikimori', 'kinopoisk', 'imdb', 'kodik'], $values);
    }
}
