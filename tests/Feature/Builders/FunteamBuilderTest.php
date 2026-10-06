<?php

declare(strict_types=1);

namespace Tests\Feature\Builders;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use App\Models\Source;
use App\Values\Source\SourceProvider;
use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FunteamBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_where_has_sources_matches_a_funteam_holding_any_of_the_given_pairs(): void
    {
        $kodikFunteam = Funteam::factory()->createOne();
        $imdbFunteam = Funteam::factory()->createOne();
        $unrelatedFunteam = Funteam::factory()->createOne();

        $this->giveSourceTo($kodikFunteam, SourceProviderName::KODIK, '735');
        $this->giveSourceTo($imdbFunteam, SourceProviderName::IMDB, '42');
        $this->giveSourceTo($unrelatedFunteam, SourceProviderName::SHIKIMORI, '77');

        $found = Funteam::query()->whereHasSources([
            SourceProvider::make(SourceProviderName::KODIK, '735'),
            SourceProvider::make(SourceProviderName::IMDB, '42'),
        ])->pluck('id')->all();

        $this->assertEqualsCanonicalizing([$kodikFunteam->id, $imdbFunteam->id], $found);
    }

    public function test_where_has_sources_ignores_a_funteam_holding_the_same_provider_under_another_external_id(): void
    {
        $funteam = Funteam::factory()->createOne();
        $this->giveSourceTo($funteam, SourceProviderName::KODIK, '735');

        $found = Funteam::query()->whereHasSources([
            SourceProvider::make(SourceProviderName::KODIK, '824'),
        ])->pluck('id')->all();

        $this->assertSame([], $found);
    }

    public function test_where_has_sources_accepts_a_generator(): void
    {
        $funteam = Funteam::factory()->createOne();
        $this->giveSourceTo($funteam, SourceProviderName::KINOPOISK, '1');

        $sources = (static function (): Generator {
            yield SourceProvider::make(SourceProviderName::KINOPOISK, '1');
        })();

        $found = Funteam::query()->whereHasSources($sources)->pluck('id')->all();

        $this->assertSame([$funteam->id], $found);
    }

    public function test_where_has_sources_without_sources_returns_nothing(): void
    {
        $funteam = Funteam::factory()->createOne();
        $this->giveSourceTo($funteam, SourceProviderName::KODIK, '735');

        $found = Funteam::query()->whereHasSources([])->pluck('id')->all();

        $this->assertSame([], $found);
    }

    public function test_where_has_sources_by_names_ignores_providers_outside_the_allowed_names(): void
    {
        $kodikFunteam = Funteam::factory()->createOne();
        $imdbOnlyFunteam = Funteam::factory()->createOne();

        $this->giveSourceTo($kodikFunteam, SourceProviderName::KODIK, '735');
        $this->giveSourceTo($kodikFunteam, SourceProviderName::IMDB, '42');
        $this->giveSourceTo($imdbOnlyFunteam, SourceProviderName::IMDB, '42');

        $found = Funteam::query()->whereHasSourcesByNames(
            [
                SourceProvider::make(SourceProviderName::KODIK, '735'),
                SourceProvider::make(SourceProviderName::IMDB, '42'),
            ],
            [SourceProviderName::KODIK],
        )->pluck('id')->all();

        $this->assertSame([$kodikFunteam->id], $found);
    }

    public function test_where_has_sources_by_names_without_allowed_names_returns_nothing(): void
    {
        $funteam = Funteam::factory()->createOne();
        $this->giveSourceTo($funteam, SourceProviderName::KODIK, '735');

        $found = Funteam::query()->whereHasSourcesByNames(
            [SourceProvider::make(SourceProviderName::KODIK, '735')],
            [],
        )->pluck('id')->all();

        $this->assertSame([], $found);
    }

    public function test_where_has_source_from_provider_ignores_the_external_id(): void
    {
        $funteam = Funteam::factory()->createOne();
        $imdbFunteam = Funteam::factory()->createOne();

        $this->giveSourceTo($funteam, SourceProviderName::KODIK, '735');
        $this->giveSourceTo($imdbFunteam, SourceProviderName::IMDB, '42');

        $found = Funteam::query()
            ->whereHasSourceFromProvider(SourceProviderName::KODIK)
            ->pluck('id')->all();

        $this->assertSame([$funteam->id], $found);
    }

    private function giveSourceTo(Funteam $funteam, SourceProviderName $providerName, string $externalId): Source
    {
        return Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider_name' => $providerName,
            'external_id' => $externalId,
        ]);
    }
}
