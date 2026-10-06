<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Enums\SourceProvider;
use App\Models\Funteam;
use App\Models\Source;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_funteam_exposes_its_sources(): void
    {
        $funteam = Funteam::factory()->createOne();
        $source = Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider' => SourceProvider::MYANIMELIST,
        ]);

        $this->assertCount(1, $funteam->sources);
        $this->assertTrue($funteam->sources->first()->is($source));
    }

    public function test_source_resolves_its_sourceable_funteam(): void
    {
        $funteam = Funteam::factory()->createOne();
        $source = Source::factory()->for($funteam, 'sourceable')->createOne();

        $this->assertTrue($source->sourceable->is($funteam));
    }

    public function test_provider_is_cast_to_enum(): void
    {
        $source = Source::factory()->createOne(['provider' => SourceProvider::KODIK]);

        $this->assertSame(SourceProvider::KODIK, $source->provider);
        $this->assertSame('kodik', $source->getRawOriginal('provider'));
    }

    public function test_for_relationship_sets_the_morph_columns(): void
    {
        $funteam = Funteam::factory()->createOne();
        $source = Source::factory()->for($funteam, 'sourceable')->createOne();

        $this->assertSame($funteam->getMorphClass(), $source->sourceable_type);
        $this->assertSame($funteam->id, $source->sourceable_id);
    }

    public function test_sourceable_and_provider_pair_is_unique(): void
    {
        $funteam = Funteam::factory()->createOne();
        Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider' => SourceProvider::SHIKIMORI,
        ]);

        $this->expectException(QueryException::class);

        Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider' => SourceProvider::SHIKIMORI,
        ]);
    }

    public function test_a_funteam_may_hold_several_providers(): void
    {
        $funteam = Funteam::factory()->createOne();
        Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider' => SourceProvider::MYANIMELIST,
        ]);
        Source::factory()->for($funteam, 'sourceable')->createOne([
            'provider' => SourceProvider::KINOPOISK,
        ]);

        $this->assertCount(2, $funteam->sources);
    }

    public function test_the_same_external_id_may_be_shared_across_sourceables(): void
    {
        $externalId = '42';

        $first = Source::factory()->for(Funteam::factory(), 'sourceable')->createOne([
            'provider' => SourceProvider::IMDB,
            'external_id' => $externalId,
        ]);
        $second = Source::factory()->for(Funteam::factory(), 'sourceable')->createOne([
            'provider' => SourceProvider::IMDB,
            'external_id' => $externalId,
        ]);

        $this->assertFalse($first->sourceable->is($second->sourceable));
        $this->assertSame($externalId, $second->external_id);
    }
}
