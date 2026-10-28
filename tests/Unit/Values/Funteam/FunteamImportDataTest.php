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

    public function test_merge_combines_and_deduplicates_the_sources_of_a_funteam(): void
    {
        $kodikSource = SourceProvider::make(SourceProviderName::KODIK, '735');
        $myAnimeListSource = SourceProvider::make(SourceProviderName::MYANIMELIST, '42');

        $merged = FunteamImportData::merge([
            FunteamImportData::make(name: '2x2', slug: '2x2', sources: [$kodikSource]),
            FunteamImportData::make(name: '2x2', slug: '2x2', sources: [$kodikSource, $myAnimeListSource]),
        ]);

        static::assertSame('2x2', $merged->name);
        static::assertSame('2x2', $merged->slug);
        static::assertEqualsCanonicalizing([$kodikSource, $myAnimeListSource], $merged->sources);
    }

    public function test_merge_rejects_rows_with_different_slugs(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot merge funteam import rows with different slugs.');

        FunteamImportData::merge([
            FunteamImportData::make(name: '2x2', slug: '2x2', sources: [SourceProvider::make(
                SourceProviderName::KODIK,
                '735',
            )]),
            FunteamImportData::make(name: '3df voice', slug: '3df-voice', sources: [SourceProvider::make(
                SourceProviderName::KODIK,
                '824',
            )]),
        ]);
    }

    public function test_merge_rejects_an_empty_row_list(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot merge an empty list of funteam import rows.');

        FunteamImportData::merge([]);
    }
}
