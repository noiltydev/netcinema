<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\SourceProviderName;
use PHPUnit\Framework\TestCase;

class SourceProviderTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        $this->assertSame('myanimelist', SourceProviderName::MYANIMELIST->value);
        $this->assertSame('shikimori', SourceProviderName::SHIKIMORI->value);
        $this->assertSame('kinopoisk', SourceProviderName::KINOPOISK->value);
        $this->assertSame('imdb', SourceProviderName::IMDB->value);
        $this->assertSame('kodik', SourceProviderName::KODIK->value);
    }

    public function test_enum_exposes_every_provider_exactly_once(): void
    {
        $values = array_column(SourceProviderName::cases(), 'value');

        $this->assertSame(
            ['myanimelist', 'shikimori', 'kinopoisk', 'imdb', 'kodik'],
            $values,
        );
    }
}
