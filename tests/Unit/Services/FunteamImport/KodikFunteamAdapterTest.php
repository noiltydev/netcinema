<?php

declare(strict_types=1);

namespace Tests\Unit\Services\FunteamImport;

use App\Enums\SourceProviderName;
use App\Services\FunteamImport\KodikFunteamAdapter;
use App\Values\Funteam\FunteamImportData;
use App\Values\Kodik\KodikTranslation;
use App\Values\Source\SourceProvider;
use PHPUnit\Framework\TestCase;

class KodikFunteamAdapterTest extends TestCase
{
    /**
     * @param array<int, array{id: int, title: string, count: int}> $translations
     */
    private function adapter(array $translations): KodikFunteamAdapter
    {
        return new KodikFunteamAdapter(array_map(KodikTranslation::fromArray(...), $translations));
    }

    public function test_maps_each_translation_to_a_funteam_with_a_kodik_source(): void
    {
        $funteams = $this->adapter([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
            ['id' => 824, 'title' => '3df voice', 'count' => 16],
        ])->funteams();

        static::assertCount(2, $funteams);

        /** @var FunteamImportData $first */
        $first = $funteams->first();
        static::assertSame('2x2', $first->name);
        static::assertSame('2x2', $first->slug);
        static::assertEquals([SourceProvider::make(SourceProviderName::KODIK, '735')], $first->sources);
    }

    public function test_groups_the_voice_and_subtitles_translations_of_a_funteam(): void
    {
        $funteams = $this->adapter([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
            ['id' => 736, 'title' => '2x2.Subtitles', 'count' => 4],
        ])->funteams();

        static::assertCount(1, $funteams);

        /** @var FunteamImportData $funteam */
        $funteam = $funteams->sole();
        static::assertSame('2x2', $funteam->name);
        static::assertSame('2x2', $funteam->slug);
        static::assertEqualsCanonicalizing(
            ['735', '736'],
            array_map(static fn (SourceProvider $source): string => $source->externalId, $funteam->sources),
        );
    }

    public function test_strips_the_subtitles_suffix_from_the_funteam_name(): void
    {
        $funteams = $this->adapter([
            ['id' => 735, 'title' => 'Название.Subtitles', 'count' => 26],
        ])->funteams();

        /** @var FunteamImportData $funteam */
        $funteam = $funteams->sole();
        static::assertSame('Название', $funteam->name);
        static::assertSame('nazvanie', $funteam->slug);
    }

    public function test_skips_translations_without_transliterable_characters(): void
    {
        $funteams = $this->adapter([
            ['id' => 1, 'title' => '!!!', 'count' => 1],
            ['id' => 2, 'title' => '2x2', 'count' => 26],
        ])->funteams();

        static::assertCount(1, $funteams);
        static::assertSame('2x2', $funteams->sole()->slug);
    }

    public function test_deduplicates_repeated_translation_ids(): void
    {
        $funteams = $this->adapter([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
            ['id' => 735, 'title' => '2x2.Subtitles', 'count' => 4],
        ])->funteams();

        /** @var FunteamImportData $funteam */
        $funteam = $funteams->sole();
        static::assertCount(1, $funteam->sources);
        static::assertSame('735', $funteam->sources[0]->externalId);
    }
}
