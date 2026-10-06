<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Kodik;

use App\Models\Funteam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class ImportFunteamsCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param array<int, array{id: int, title: string, count: int}> $results
     */
    private function fakeKodikTranslations(array $results): void
    {
        Saloon::fake([
            '*' => MockResponse::make([
                'time' => '5ms',
                'total' => count($results),
                'results' => $results,
            ]),
        ]);
    }

    public function test_imports_funteams_from_kodik_translations(): void
    {
        $this->fakeKodikTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
            ['id' => 824, 'title' => '3df voice', 'count' => 16],
        ]);

        $this->artisan('kodik:funteams:import')->assertSuccessful();

        $this->assertDatabaseHas('funteams', ['name' => '2x2', 'slug' => '2x2']);
        $this->assertDatabaseHas('funteams', ['name' => '3df voice', 'slug' => '3df-voice']);
        $this->assertDatabaseCount('funteams', 2);
    }

    public function test_imports_funteams_without_subtitles_suffix(): void
    {
        $this->fakeKodikTranslations([
            ['id' => 735, 'title' => 'Название.Subtitles', 'count' => 26],
        ]);

        $this->artisan('kodik:funteams:import')->assertSuccessful();

        $this->assertDatabaseHas('funteams', ['name' => 'Название', 'slug' => 'nazvanie']);
    }

    public function test_skips_translations_without_transliterable_characters(): void
    {
        $this->fakeKodikTranslations([
            ['id' => 1, 'title' => '!!!', 'count' => 1],
            ['id' => 2, 'title' => '2x2', 'count' => 26],
        ]);

        $this->artisan('kodik:funteams:import')->assertSuccessful();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseHas('funteams', ['name' => '2x2', 'slug' => '2x2']);
    }

    public function test_keeps_first_translation_for_duplicated_slugs(): void
    {
        $this->fakeKodikTranslations([
            ['id' => 1, 'title' => 'Название', 'count' => 26],
            ['id' => 2, 'title' => 'Название.Subtitles', 'count' => 4],
        ]);

        $this->artisan('kodik:funteams:import')->assertSuccessful();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertDatabaseHas('funteams', ['name' => 'Название', 'slug' => 'nazvanie']);
    }

    public function test_updates_existing_funteam_and_keeps_its_creation_date(): void
    {
        Carbon::setTestNow('2026-01-01 00:00:00');

        $funteam = Funteam::factory()->createOne([
            'name' => 'Old name',
            'slug' => '2x2',
        ]);

        Carbon::setTestNow('2026-02-01 00:00:00');

        $this->fakeKodikTranslations([
            ['id' => 735, 'title' => '2x2', 'count' => 26],
        ]);

        $this->artisan('kodik:funteams:import')->assertSuccessful();

        $funteam->refresh();

        $this->assertDatabaseCount('funteams', 1);
        $this->assertSame('2x2', $funteam->name);
        $this->assertTrue($funteam->created_at->equalTo('2026-01-01 00:00:00'));
        $this->assertTrue($funteam->updated_at->equalTo('2026-02-01 00:00:00'));

        Carbon::setTestNow();
    }

    public function test_succeeds_without_translations(): void
    {
        $this->fakeKodikTranslations([]);

        $this->artisan('kodik:funteams:import')
            ->expectsOutputToContain('Imported 0 funteams')
            ->assertSuccessful();

        $this->assertDatabaseCount('funteams', 0);
    }
}
