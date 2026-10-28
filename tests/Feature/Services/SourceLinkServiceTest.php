<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\SourceProviderName;
use App\Models\Anime;
use App\Models\Funteam;
use App\Models\Source;
use App\Services\SourceLinkService;
use App\Values\Source\SourceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class SourceLinkServiceTest extends TestCase
{
    use RefreshDatabase;

    private function service(): SourceLinkService
    {
        return $this->app->make(SourceLinkService::class);
    }

    public function test_attaches_missing_sources_to_the_sourceable(): void
    {
        $funteam = Funteam::factory()->createOne();
        $source = SourceProvider::make(SourceProviderName::KODIK, '735');
        $linkedSourceableIds = $this->service()->getLinkedSourceableIds(Funteam::class, [$source]);

        $this->service()->attachSources($funteam, [$source], $linkedSourceableIds, now());

        $storedSource = $funteam->sources()->sole();

        static::assertSame(SourceProviderName::KODIK, $storedSource->provider_name);
        static::assertSame('735', $storedSource->external_id);
        static::assertSame($funteam->id, $linkedSourceableIds->get('kodik:735'));
    }

    public function test_does_not_duplicate_a_source_already_linked_to_the_sourceable(): void
    {
        $funteam = Funteam::factory()->createOne();
        Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);
        $source = SourceProvider::make(SourceProviderName::KODIK, '735');
        $linkedSourceableIds = $this->service()->getLinkedSourceableIds(Funteam::class, [$source]);

        $this->service()->attachSources($funteam, [$source], $linkedSourceableIds, now());

        static::assertCount(1, $funteam->sources);
    }

    public function test_deduplicates_duplicate_sources_in_the_given_list(): void
    {
        $funteam = Funteam::factory()->createOne();
        $source = SourceProvider::make(SourceProviderName::KODIK, '735');
        $linkedSourceableIds = $this->service()->getLinkedSourceableIds(Funteam::class, [$source]);

        $this->service()->attachSources($funteam, [$source, $source], $linkedSourceableIds, now());

        static::assertCount(1, $funteam->sources);
    }

    public function test_throws_when_a_source_is_already_linked_to_another_sourceable(): void
    {
        $funteam = Funteam::factory()->createOne();
        $linkedFunteam = Funteam::factory()->createOne();
        Source::factory()->for($linkedFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);
        $source = SourceProvider::make(SourceProviderName::KODIK, '735');
        $linkedSourceableIds = $this->service()->getLinkedSourceableIds(Funteam::class, [$source]);

        $this->assertThrows(
            fn () => $this->service()->attachSources($funteam, [$source], $linkedSourceableIds, now()),
            RuntimeException::class,
            sprintf(
                'Source kodik:735 is already linked to funteam #%d and cannot be linked to funteam #%d.',
                $linkedFunteam->id,
                $funteam->id,
            ),
        );

        static::assertCount(0, $funteam->sources);
    }

    public function test_the_same_external_id_on_a_different_sourceable_type_does_not_conflict(): void
    {
        $anime = Anime::factory()->createOne();
        Source::factory()->for($anime, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);
        $funteam = Funteam::factory()->createOne();
        $source = SourceProvider::make(SourceProviderName::KODIK, '735');
        $linkedSourceableIds = $this->service()->getLinkedSourceableIds(Funteam::class, [$source]);

        $this->service()->attachSources($funteam, [$source], $linkedSourceableIds, now());

        $this->assertDatabaseCount('sources', 2);
        static::assertSame('735', $funteam->sources()->sole()->external_id);
    }
}
