<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use App\Models\Source;
use App\Services\FunteamImportService;
use App\Values\Kodik\KodikTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use RuntimeException;
use Tests\TestCase;

class FunteamImportServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param array<int, array{id: int, title: string, count: int}> $translations
     *
     * @return array<int, KodikTranslation>
     */
    private function makeTranslations(array $translations): array
    {
        return array_map(KodikTranslation::fromArray(...), $translations);
    }

    public function test_import_links_every_imported_funteam_to_its_kodik_translation(): void
    {
        $this->app
            ->make(FunteamImportService::class)
            ->importFromKodik($this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 824, 'title' => '3df voice', 'count' => 16],
            ]));

        $firstFunteam = Funteam::query()->where('slug', '2x2')->sole();
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

    public function test_import_links_the_voice_and_subtitles_translations_of_a_funteam_to_that_funteam(): void
    {
        $this->app
            ->make(FunteamImportService::class)
            ->importFromKodik($this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 736, 'title' => '2x2.Subtitles', 'count' => 4],
            ]));

        $funteam = Funteam::query()->where('slug', '2x2')->sole();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 2);
        static::assertEqualsCanonicalizing(['735', '736'], $funteam->sources()->pluck('external_id')->all());
    }

    public function test_import_is_idempotent_for_a_funteam_with_two_kodik_translations(): void
    {
        $service = $this->app->make(FunteamImportService::class);
        $translations = $this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
            ['id' => 736, 'title' => '2x2.Subtitles', 'count' => 4],
        ]);

        $service->importFromKodik($translations);
        $service->importFromKodik($translations);

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 2);
    }

    public function test_import_links_a_funteam_that_existed_without_a_kodik_source(): void
    {
        $funteam = Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);

        $this->app
            ->make(FunteamImportService::class)
            ->importFromKodik($this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
            ]));

        /** @var Source $source */
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

        $this->app
            ->make(FunteamImportService::class)
            ->importFromKodik($this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
            ]));

        /** @var Collection<int, Source> $providers */
        $providers = $funteam->sources()->orderBy('provider_name')->get();

        static::assertSame(
            [SourceProviderName::KODIK, SourceProviderName::MYANIMELIST],
            $providers->map(static fn (Source $source): SourceProviderName => $source->provider_name)->all(),
        );
        static::assertSame(
            ['735', '42'],
            $providers->map(static fn (Source $source): string => $source->external_id)->all(),
        );
    }

    public function test_import_is_idempotent(): void
    {
        $service = $this->app->make(FunteamImportService::class);
        $translations = $this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
        ]);

        $service->importFromKodik($translations);
        $service->importFromKodik($translations);

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseHas('sources', [
            'provider_name' => SourceProviderName::KODIK->value,
            'external_id' => '735',
        ]);
    }

    public function test_import_keeps_the_name_of_an_already_linked_funteam(): void
    {
        $service = $this->app->make(FunteamImportService::class);

        $service->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
        ]));
        $service->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2 renamed', 'count' => 26],
        ]));

        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseHas('funteams', ['name' => '2x2', 'slug' => '2x2']);
    }

    public function test_import_throws_when_a_kodik_translation_is_already_linked_to_another_funteam(): void
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
            fn () => $this->app
                ->make(FunteamImportService::class)
                ->importFromKodik($this->makeTranslations([
                    ['id' => 735, 'title' => '2x2', 'count' => 26],
                ])),
            RuntimeException::class,
            sprintf(
                'Kodik translation 735 is already linked to funteam #%d and cannot be linked to funteam #%d.',
                $linkedFunteam->id,
                $funteam->id,
            ),
        );

        $this->assertDatabaseCount('sources', 1);
        static::assertTrue($linkedFunteam->sources()->sole()->is($source));
    }

    public function test_import_rolls_back_the_chunk_when_a_kodik_translation_is_already_linked_to_another_funteam(): void
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
            fn () => $this->app
                ->make(FunteamImportService::class)
                ->importFromKodik($this->makeTranslations([
                    ['id' => 824, 'title' => '3df voice', 'count' => 16],
                    ['id' => 735, 'title' => '2x2', 'count' => 26],
                ])),
            RuntimeException::class,
        );

        $this->assertDatabaseCount('funteams', 2);
        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseMissing('funteams', ['slug' => '3df-voice']);
    }

    public function test_import_reports_the_processed_chunk_size_to_the_progress_callback(): void
    {
        $processedCount = 0;

        $importedCount = $this->app->make(FunteamImportService::class)->importFromKodik(
            $this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 824, 'title' => '!!!', 'count' => 16],
            ]),
            static function (int $count) use (&$processedCount): void {
                $processedCount += $count;
            },
        );

        static::assertSame(1, $importedCount);
        static::assertSame(1, $processedCount);
        $this->assertDatabaseCount('sources', 1);
    }
}
