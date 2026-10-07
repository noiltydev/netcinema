<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use App\Models\Source;
use App\Services\FunteamService;
use App\Values\Kodik\KodikTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class FunteamServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param array<int, array{id: int, title: string, count: int}> $translations
     *
     * @return array<int, KodikTranslation>
     */
    private function makeTranslations(array $translations): array
    {
        return array_map(
            static fn(array $translation): KodikTranslation => KodikTranslation::fromArray($translation),
            $translations,
        );
    }

    public function test_import_links_every_imported_funteam_to_its_kodik_translation(): void
    {
        $this->app->make(FunteamService::class)->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
            ['id' => 824, 'title' => '3df voice', 'count' => 16],
        ]));

        /** @var Funteam $firstFunteam */
        $firstFunteam = Funteam::query()->where('slug', '2x2')->sole();
        /** @var Funteam $secondFunteam */
        $secondFunteam = Funteam::query()->where('slug', '3df-voice')->sole();

        $this->assertDatabaseCount('sources', 2);
        $this->assertSame(SourceProviderName::KODIK, $firstFunteam->sources()->sole()->provider_name);
        $this->assertSame('735', $firstFunteam->sources()->sole()->external_id);
        $this->assertSame(SourceProviderName::KODIK, $secondFunteam->sources()->sole()->provider_name);
        $this->assertSame('824', $secondFunteam->sources()->sole()->external_id);
    }

    public function test_import_links_a_funteam_that_existed_without_a_kodik_source(): void
    {
        $funteam = Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);

        $this->app->make(FunteamService::class)->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
        ]));

        $source = $funteam->sources()->sole();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertSame(SourceProviderName::KODIK, $source->provider_name);
        $this->assertSame('735', $source->external_id);
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

        $this->app->make(FunteamService::class)->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
        ]));

        $providers = $funteam->sources()->orderBy('provider_name')->get();

        $this->assertCount(2, $providers);
        $this->assertSame(SourceProviderName::KODIK, $providers->first()->provider_name);
        $this->assertSame(SourceProviderName::MYANIMELIST, $providers->last()->provider_name);
        $this->assertSame('735', $providers->first()->external_id);
        $this->assertSame('42', $providers->last()->external_id);
    }

    public function test_import_is_idempotent(): void
    {
        $service = $this->app->make(FunteamService::class);
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
        $service = $this->app->make(FunteamService::class);

        $service->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
        ]));
        $service->importFromKodik($this->makeTranslations([
            ['id' => 735, 'title' => '2x2 renamed', 'count' => 26],
        ]));

        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseHas('funteams', ['name' => '2x2', 'slug' => '2x2']);
    }

    public function test_import_throws_when_a_funteam_is_linked_to_another_kodik_translation(): void
    {
        $funteam = Funteam::factory()->createOne([
            'name' => '2x2',
            'slug' => '2x2',
        ]);
        $source = Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '111',
        ]);

        $this->assertThrows(
            fn() => $this->app->make(FunteamService::class)->importFromKodik($this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
            ])),
            RuntimeException::class,
            'Funteam "2x2" is already linked to Kodik translation 111 and cannot be linked to translation 735.',
        );

        $this->assertDatabaseCount('sources', 1);
        $this->assertTrue($funteam->sources()->sole()->is($source));
    }

    public function test_import_rolls_back_the_chunk_when_a_funteam_is_linked_to_another_kodik_translation(): void
    {
        Funteam::factory()->createOne([
            'name' => 'Original name',
            'slug' => '2x2',
        ]);
        Source::factory()->for(Funteam::query()->where('slug', '2x2')->sole(), 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '111',
        ]);

        $this->assertThrows(
            fn() => $this->app->make(FunteamService::class)->importFromKodik($this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
            ])),
            RuntimeException::class,
        );

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseCount('sources', 1);
        $this->assertDatabaseHas('funteams', ['slug' => '2x2', 'name' => 'Original name']);
    }

    public function test_import_reports_the_processed_chunk_size_to_the_progress_callback(): void
    {
        $processedCount = 0;

        $importedCount = $this->app->make(FunteamService::class)->importFromKodik(
            $this->makeTranslations([
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 824, 'title' => '!!!', 'count' => 16],
            ]),
            static function (int $count) use (&$processedCount): void {
                $processedCount += $count;
            },
        );

        $this->assertSame(1, $importedCount);
        $this->assertSame(1, $processedCount);
        $this->assertDatabaseCount('sources', 1);
    }
}
