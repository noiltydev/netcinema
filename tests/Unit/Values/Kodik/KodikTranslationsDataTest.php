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
            'total' => 30590,
            'results' => [
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 824, 'title' => '3df voice', 'count' => 16],
            ],
        ];

        $dto = KodikTranslationsData::fromArray($data);

        $this->assertSame('5ms', $dto->time);
        $this->assertSame(30590, $dto->total);
        $this->assertCount(2, $dto->results);
        $this->assertSame(735, $dto->results[0]->id);
        $this->assertSame('2x2', $dto->results[0]->title);
        $this->assertSame(26, $dto->results[0]->count);
        $this->assertSame(824, $dto->results[1]->id);
        $this->assertSame('3df voice', $dto->results[1]->title);
        $this->assertSame(16, $dto->results[1]->count);
    }

    public function test_from_array_handles_empty_results(): void
    {
        $data = [
            'time' => '2ms',
            'total' => 0,
            'results' => [],
        ];

        $dto = KodikTranslationsData::fromArray($data);

        $this->assertSame('2ms', $dto->time);
        $this->assertSame(0, $dto->total);
        $this->assertSame([], $dto->results);
    }

    public function test_from_array_handles_missing_optional_fields(): void
    {
        $data = [
            'results' => [],
        ];

        $dto = KodikTranslationsData::fromArray($data);

        $this->assertSame('', $dto->time);
        $this->assertSame(0, $dto->total);
        $this->assertSame([], $dto->results);
    }
}
