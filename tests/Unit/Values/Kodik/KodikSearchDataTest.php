<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Values\Kodik\KodikMaterial;
use App\Values\Kodik\KodikSearchData;
use PHPUnit\Framework\TestCase;

class KodikSearchDataTest extends TestCase
{
    public function test_from_array_maps_all_fields(): void
    {
        $data = [
            'time' => '5ms',
            'total' => 2,
            'results' => [
                ['id' => 'movie-452654', 'type' => 'foreign-movie', 'title' => 'Спортлото-82', 'year' => 1982],
                ['id' => 'movie-452655', 'type' => 'anime-serial', 'title' => 'Игра престолов'],
            ],
        ];

        $dto = KodikSearchData::fromArray($data);

        static::assertSame('5ms', $dto->time);
        static::assertSame(2, $dto->total);
        static::assertCount(2, $dto->results);
        static::assertSame('movie-452654', $dto->results[0]->id);
        static::assertSame('Спортлото-82', $dto->results[0]->title);
        static::assertSame(1982, $dto->results[0]->year);
        static::assertSame('movie-452655', $dto->results[1]->id);
        static::assertNull($dto->results[1]->year);
    }

    public function test_from_array_handles_empty_results(): void
    {
        $dto = KodikSearchData::fromArray([
            'time' => '2ms',
            'total' => 0,
            'results' => [],
        ]);

        static::assertSame('2ms', $dto->time);
        static::assertSame(0, $dto->total);
        static::assertSame([], $dto->results);
    }

    public function test_from_array_handles_missing_fields(): void
    {
        $dto = KodikSearchData::fromArray([]);

        static::assertSame('', $dto->time);
        static::assertSame(0, $dto->total);
        static::assertSame([], $dto->results);
    }

    public function test_make_defaults_to_empty_payload(): void
    {
        $dto = KodikSearchData::make();

        static::assertSame('', $dto->time);
        static::assertSame(0, $dto->total);
        static::assertSame([], $dto->results);
        static::assertSame(['time' => '', 'total' => 0, 'results' => []], $dto->toArray());
    }

    public function test_to_array_round_trips_materials(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'type' => 'foreign-movie',
            'title' => 'Спортлото-82',
        ]);

        $dto = KodikSearchData::make(time: '5ms', total: 1, results: [$material]);

        static::assertSame(
            [
                'time' => '5ms',
                'total' => 1,
                'results' => [$material->toArray()],
            ],
            $dto->toArray(),
        );
    }
}
