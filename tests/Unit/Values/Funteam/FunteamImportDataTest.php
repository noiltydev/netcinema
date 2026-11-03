<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Funteam;

use App\Enums\SourceProviderName;
use App\Values\Funteam\FunteamImportData;
use App\Values\Source\SourceProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FunteamImportDataTest extends TestCase
{
    public function test_make_populates_the_funteam_identity_and_sources(): void
    {
        $source = SourceProvider::make(SourceProviderName::KODIK, '735');

        $data = FunteamImportData::make(name: '2x2', slug: '2x2', sources: [$source]);

        static::assertSame('2x2', $data->name);
        static::assertSame('2x2', $data->slug);
        static::assertSame([$source], $data->sources);
    }

    public function test_make_rejects_an_empty_slug(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Funteam name "!!!" does not produce a slug.');

        FunteamImportData::make(name: '!!!', slug: '', sources: [SourceProvider::make(
            SourceProviderName::KODIK,
            '735',
        )]);
    }

    public function test_make_rejects_an_empty_source_list(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Funteam "2x2" must have at least one source.');

        FunteamImportData::make(name: '2x2', slug: '2x2', sources: []);
    }
}
