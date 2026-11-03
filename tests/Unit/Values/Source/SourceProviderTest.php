<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Source;

use App\Enums\SourceProviderName;
use App\Values\Source\SourceProvider;
use PHPUnit\Framework\TestCase;

class SourceProviderTest extends TestCase
{
    public function test_make_populates_provider_name_and_external_id(): void
    {
        $provider = SourceProvider::make(SourceProviderName::KINOPOISK, '42');

        static::assertSame(SourceProviderName::KINOPOISK, $provider->providerName);
        static::assertSame('42', $provider->externalId);
    }

    public function test_to_array_exposes_the_sources_table_columns(): void
    {
        $provider = SourceProvider::make(SourceProviderName::SHIKIMORI, '735');

        static::assertSame(
            ['provider_name' => SourceProviderName::SHIKIMORI, 'external_id' => '735'],
            $provider->toArray(),
        );
    }

    public function test_key_combines_the_provider_name_and_external_id(): void
    {
        $provider = SourceProvider::make(SourceProviderName::KODIK, '735');

        static::assertSame('kodik:735', $provider->key());
    }
}
