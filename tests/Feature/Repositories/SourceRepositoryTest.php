<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Enums\SourceProviderName;
use App\Models\Anime;
use App\Models\Funteam;
use App\Models\Source;
use App\Repositories\SourceRepository;
use App\Values\Source\SourceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_sourceable_ids_keyed_by_provider_and_external_id(): void
    {
        $firstFunteam = Funteam::factory()->createOne();
        $secondFunteam = Funteam::factory()->createOne();
        Source::factory()->for($firstFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);
        Source::factory()->for($secondFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '824',
        ]);

        $linkedSourceableIds = $this->repository()->getSourceableIdsBySources(Funteam::class, collect([
            SourceProvider::make(SourceProviderName::KODIK, '735'),
            SourceProvider::make(SourceProviderName::KODIK, '824'),
        ]));

        static::assertSame($firstFunteam->id, $linkedSourceableIds->get('kodik:735'));
        static::assertSame($secondFunteam->id, $linkedSourceableIds->get('kodik:824'));
    }

    public function test_ignores_sources_of_another_sourceable_type(): void
    {
        $anime = Anime::factory()->createOne();
        Source::factory()->for($anime, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '735',
        ]);

        $linkedSourceableIds = $this->repository()->getSourceableIdsBySources(Funteam::class, collect([SourceProvider::make(
            SourceProviderName::KODIK,
            '735',
        )]));

        static::assertCount(0, $linkedSourceableIds);
    }

    public function test_distinguishes_the_same_external_id_across_providers(): void
    {
        $kodikFunteam = Funteam::factory()->createOne();
        $myAnimeListFunteam = Funteam::factory()->createOne();
        Source::factory()->for($kodikFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::KODIK,
            'external_id' => '42',
        ]);
        Source::factory()->for($myAnimeListFunteam, 'sourceable')->createOne([
            'provider_name' => SourceProviderName::MYANIMELIST,
            'external_id' => '42',
        ]);

        $linkedSourceableIds = $this->repository()->getSourceableIdsBySources(Funteam::class, collect([
            SourceProvider::make(SourceProviderName::KODIK, '42'),
            SourceProvider::make(SourceProviderName::MYANIMELIST, '42'),
        ]));

        static::assertSame($kodikFunteam->id, $linkedSourceableIds->get('kodik:42'));
        static::assertSame($myAnimeListFunteam->id, $linkedSourceableIds->get('myanimelist:42'));
    }

    public function test_returns_an_empty_collection_without_sources(): void
    {
        $linkedSourceableIds = $this->repository()->getSourceableIdsBySources(Funteam::class, collect());

        static::assertCount(0, $linkedSourceableIds);
    }

    private function repository(): SourceRepository
    {
        return $this->app->make(SourceRepository::class);
    }
}
