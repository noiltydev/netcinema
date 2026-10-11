<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Values\Kodik\KodikTranslationsData;
use PHPUnit\Framework\TestCase;

class KodikTranslationsDataTest extends TestCase
{
    public function test_from_array_maps_all_fields(): void
    {
        $data = [
            'time' => '5ms',
            'total' => 30_590,
            'results' => [
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 824, 'title' => '3df voice', 'count' => 16],
            ],
        ];

        $dto = KodikTranslationsData::fromArray($data);

        static::assertSame('5ms', $dto->time);
        static::assertSame(30_590, $dto->total);
        static::assertCount(2, $dto->results);
        static::assertSame(735, $dto->results[0]->id);
        static::assertSame('2x2', $dto->results[0]->title);
        static::assertSame(26, $dto->results[0]->count);
        static::assertSame(824, $dto->results[1]->id);
        static::assertSame('3df voice', $dto->results[1]->title);
        static::assertSame(16, $dto->results[1]->count);
    }

    public function test_from_array_handles_empty_results(): void
    {
        $data = [
            'time' => '2ms',
            'total' => 0,
            'results' => [],
        ];

        $dto = KodikTranslationsData::fromArray($data);

        static::assertSame('2ms', $dto->time);
        static::assertSame(0, $dto->total);
        static::assertSame([], $dto->results);
    }

    public function test_from_array_handles_missing_optional_fields(): void
    {
        $data = [
            'results' => [],
        ];

        $dto = KodikTranslationsData::fromArray($data);

        static::assertSame('', $dto->time);
        static::assertSame(0, $dto->total);
        static::assertSame([], $dto->results);
    }
}
