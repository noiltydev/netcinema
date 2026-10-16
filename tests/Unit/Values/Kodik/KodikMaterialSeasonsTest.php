<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Values\Kodik\KodikMaterialSeasons;
use PHPUnit\Framework\TestCase;

class KodikMaterialSeasonsTest extends TestCase
{
    public function test_from_array_reads_episode_numbers_as_object_keys(): void
    {
        $seasons = KodikMaterialSeasons::fromArray([
            '5' => [
                'link' => 'http://kodikplayer.com/season/27856/e3135c7e/720p',
                'episodes' => [
                    '1' => 'http://kodikplayer.com/seria/119601/09249413/720p',
                    '10' => 'http://kodikplayer.com/seria/119610/c37539d2/720p',
                ],
            ],
        ]);

        static::assertSame([5], array_keys($seasons->seasons));
        static::assertArrayHasKey(1, $seasons->seasons[5]->episodes);
        static::assertArrayHasKey(10, $seasons->seasons[5]->episodes);
        static::assertSame(
            'http://kodikplayer.com/seria/119601/09249413/720p',
            $seasons->seasons[5]->episodes[1]->link,
        );
        static::assertNull($seasons->seasons[5]->episodes[1]->title);
        static::assertSame([], $seasons->seasons[5]->episodes[1]->screenshots);
    }

    public function test_from_array_reads_the_episode_data_shape(): void
    {
        $seasons = KodikMaterialSeasons::fromArray([
            '1' => [
                'link' => 'http://kodikplayer.com/season/1/hash/720p',
                'episodes' => [
                    '1' => [
                        'link' => 'http://kodikplayer.com/seria/1/hash/720p',
                        'title' => 'Пилот',
                        'screenshots' => ['https://i.kodikres.com/screenshots/video/1/1.jpg'],
                    ],
                ],
            ],
        ]);

        $episode = $seasons->seasons[1]->episodes[1];

        static::assertSame('http://kodikplayer.com/seria/1/hash/720p', $episode->link);
        static::assertSame('Пилот', $episode->title);
        static::assertSame(['https://i.kodikres.com/screenshots/video/1/1.jpg'], $episode->screenshots);
    }

    public function test_from_array_handles_a_season_without_episodes(): void
    {
        $seasons = KodikMaterialSeasons::fromArray([
            '1' => ['link' => 'http://kodikplayer.com/season/1/hash/720p'],
        ]);

        static::assertSame([], $seasons->seasons[1]->episodes);
    }

    public function test_make_defaults_to_no_seasons(): void
    {
        static::assertSame([], KodikMaterialSeasons::make()->seasons);
    }

    public function test_to_array_round_trips_through_from_array(): void
    {
        $seasons = KodikMaterialSeasons::fromArray([
            '1' => ['link' => 'http://season', 'episodes' => ['1' => 'http://seria']],
        ]);

        static::assertSame($seasons->toArray(), KodikMaterialSeasons::fromArray($seasons->toArray())->toArray());
    }
}
