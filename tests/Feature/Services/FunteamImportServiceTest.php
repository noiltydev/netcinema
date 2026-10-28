<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use App\Models\Source;
use App\Services\FunteamImport\FunteamImportService;
use App\Values\Funteam\FunteamImportData;
use App\Values\Source\SourceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use RuntimeException;
use Tests\TestCase;

class FunteamImportServiceTest extends TestCase
{
    use RefreshDatabase;

    private function service(): FunteamImportService
    {
        return $this->app->make(FunteamImportService::class);
    }

    private function makeFunteam(
        string $name,
        SourceProviderName $providerName,
        string ...$externalIds,
    ): FunteamImportData {
        return FunteamImportData::make(
            name: $name,
            slug: slugify($name),
            sources: array_map(static fn (string $externalId): SourceProvider => SourceProvider::make(
                $providerName,
                $externalId,
            ), $externalIds),
        );
    }

    public function test_import_links_every_imported_funteam_to_its_sources(): void
    {
        $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
            $this->makeFunteam('3df voice', SourceProviderName::KODIK, '824'),
        ]);

        /** @var Funteam $firstFunteam */
        $firstFunteam = Funteam::query()->where('slug', '2x2')->sole();
        /** @var Funteam $secondFunteam */
        $secondFunteam = Funteam::query()->where('slug', '3df-voice')->sole();

        /** @var Source $firstSource */
        $firstSource = $firstFunteam->sources()->sole();
        /** @var Source $secondSource */
        $secondSource = $secondFunteam->sources()->sole();

        $this->assertDatabaseCount('sources', 2);
        static::assertSame(SourceProviderName::KODIK, $firstSource->provider_name);
        static::assertSame('735', $firstSource->external_id);
        static::assertSame(SourceProviderName::KODIK, $secondSource->provider_name);
        static::assertSame('824', $secondSource->external_id);
    }

    public function test_import_links_every_source_of_a_funteam_to_that_funteam(): void
    {
        $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735', '736'),
        ]);

        /** @var Funteam $funteam */
        $funteam = Funteam::query()->where('slug', '2x2')->sole();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 2);
        static::assertEqualsCanonicalizing(['735', '736'], $funteam->sources()->pluck('external_id')->all());
    }

    public function test_import_merges_rows_with_the_same_slug_into_one_funteam(): void
    {
        $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
            $this->makeFunteam('2x2', SourceProviderName::MYANIMELIST, '42'),
        ]);

        /** @var Funteam $funteam */
        $funteam = Funteam::query()->where('slug', '2x2')->sole();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 2);
        static::assertEqualsCanonicalizing(
            [SourceProviderName::KODIK, SourceProviderName::MYANIMELIST],
            $funteam
                ->sources()
                ->get()
                ->map(static fn (Source $source): SourceProviderName => $source->provider_name)
                ->all(),
        );
    }

    public function test_import_is_idempotent_for_a_funteam_with_two_sources(): void
    {
        $funteams = [
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735', '736'),
        ];

        $this->service()->import($funteams);
        $this->service()->import($funteams);

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 2);
    }

    public function test_import_links_a_funteam_that_existed_without_a_source(): void
    {
        $funteam = Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);

        $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
        ]);

        $source = $funteam->sources()->sole();

        $this->assertDatabaseCount('funteams', 1);
        static::assertSame(SourceProviderName::KODIK, $source->provider_name);
        static::assertSame('735', $source->external_id);
    }

    public function test_import_keeps_the_sources_of_another_provider_intact(): void
    {
        $funteam = Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);
        Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::MYANIMELIST,
            'external_id' => '42',
        ]);

        $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
        ]);

        /** @var Collection<int, Source> $sources */
        $sources = $funteam->sources()->orderBy('provider_name')->get();

        static::assertSame(
            [SourceProviderName::KODIK, SourceProviderName::MYANIMELIST],
            $sources->map(static fn (Source $source): SourceProviderName => $source->provider_name)->all(),
        );
        static::assertSame(
            ['735', '42'],
            $sources->map(static fn (Source $source): string => $source->external_id)->all(),
        );
    }

    public function test_import_is_idempotent(): void
    {
        $funteams = [
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
        ];

        $this->service()->import($funteams);
        $this->service()->import($funteams);

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseHas('sources', [
            'provider_name' => SourceProviderName::KODIK->value,
            'external_id' => '735',
        ]);
    }

    public function test_import_keeps_the_name_of_an_already_linked_funteam(): void
    {
        $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
        ]);
        $this->service()->import([
            $this->makeFunteam('2x2 renamed', SourceProviderName::KODIK, '735'),
        ]);

        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseHas('funteams', ['name' => '2x2', 'slug' => '2x2']);
    }

    public function test_import_throws_when_a_source_is_already_linked_to_another_funteam(): void
    {
        $funteam = Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);
        $linkedFunteam = Funteam::factory()->createOne([
            'name' => 'Other team',
            'slug' => 'other-team',
        ]);
        $source = Source::factory()->for($linkedFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);

        $this->assertThrows(
            fn () => $this->service()->import([
                $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
            ]),
            RuntimeException::class,
            sprintf(
                'Source kodik:735 is already linked to funteam #%d and cannot be linked to funteam #%d.',
                $linkedFunteam->id,
                $funteam->id,
            ),
        );

        $this->assertDatabaseCount('sources', 1);
        static::assertTrue($linkedFunteam->sources()->sole()->is($source));
    }

    public function test_import_rolls_back_the_chunk_when_a_source_is_already_linked_to_another_funteam(): void
    {
        Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);
        $linkedFunteam = Funteam::factory()->createOne([
            'name' => 'Other team',
            'slug' => 'other-team',
        ]);
        Source::factory()->for($linkedFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);

        $this->assertThrows(
            fn () => $this->service()->import([
                $this->makeFunteam('3df voice', SourceProviderName::KODIK, '824'),
                $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
            ]),
            RuntimeException::class,
        );

        $this->assertDatabaseCount('funteams', 2);
        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseMissing('funteams', ['slug' => '3df-voice']);
    }

    public function test_import_reports_the_processed_chunk_size_to_the_progress_callback(): void
    {
        $processedCount = 0;

        $importedCount = $this->service()->import([
            $this->makeFunteam('2x2', SourceProviderName::KODIK, '735'),
            $this->makeFunteam('3df voice', SourceProviderName::KODIK, '824'),
        ], static function (int $count) use (&$processedCount): void {
            $processedCount += $count;
        });

        static::assertSame(2, $importedCount);
        static::assertSame(2, $processedCount);
        $this->assertDatabaseCount('sources', 2);
    }
}
