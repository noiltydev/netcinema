<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Values\Kodik\KodikListData;
use App\Values\Kodik\KodikMaterial;
use PHPUnit\Framework\TestCase;

class KodikListDataTest extends TestCase
{
    public function test_from_array_maps_all_fields(): void
    {
        $data = [
            'time' => '5ms',
            'total' => 30590,
            'prev_page' => null,
            'next_page' => 'https://kodik-api.com/list?token=TOKEN&next=WyIyMDIzLTEwLTExVDA4OjE2OjUwWiIsIjUxMzk1Il0=',
            'results' => [
                [
                    'id' => 'movie-452654',
                    'type' => 'foreign-movie',
                    'title' => 'Спортлото-82',
                    'year' => 1982,
                ],
            ],
        ];

        $dto = KodikListData::fromArray($data);

        $this->assertSame('5ms', $dto->time);
        $this->assertSame(30590, $dto->total);
        $this->assertNull($dto->prevPage);
        $this->assertSame($data['next_page'], $dto->nextPage);
        $this->assertCount(1, $dto->results);
        $this->assertInstanceOf(KodikMaterial::class, $dto->results[0]);
        $this->assertSame('movie-452654', $dto->results[0]->id);
    }

    public function test_next_cursor_is_extracted_from_next_page_url(): void
    {
        $dto = KodikListData::fromArray([
            'total' => 1,
            'next_page' => 'https://kodik-api.com/list?token=TOKEN&types=anime&next=WyIyMDIzLTEwLTExVDA4OjE2OjUwWiIsIjUxMzk1Il0=',
            'results' => [],
        ]);

        $this->assertSame('WyIyMDIzLTEwLTExVDA4OjE2OjUwWiIsIjUxMzk1Il0=', $dto->nextCursor());
    }

    public function test_prev_cursor_is_extracted_from_prev_page_url(): void
    {
        $dto = KodikListData::fromArray([
            'total' => 1,
            'prev_page' => 'https://kodik-api.com/list?token=TOKEN&next=WyJwcmV2LWN1cnNvciJ9',
            'results' => [],
        ]);

        $this->assertSame('WyJwcmV2LWN1cnNvciJ9', $dto->prevCursor());
        $this->assertNull($dto->nextCursor());
    }

    public function test_cursors_are_null_when_page_urls_are_null(): void
    {
        $dto = KodikListData::fromArray([
            'total' => 0,
            'prev_page' => null,
            'next_page' => null,
            'results' => [],
        ]);

        $this->assertNull($dto->nextCursor());
        $this->assertNull($dto->prevCursor());
        $this->assertFalse($dto->hasNextPage());
    }

    public function test_cursor_is_null_when_page_url_has_no_next_parameter(): void
    {
        $dto = KodikListData::fromArray([
            'total' => 1,
            'next_page' => 'https://kodik-api.com/list?token=TOKEN',
            'results' => [],
        ]);

        $this->assertNull($dto->nextCursor());
        $this->assertFalse($dto->hasNextPage());
    }

    public function test_has_next_page_is_true_when_cursor_is_present(): void
    {
        $dto = KodikListData::fromArray([
            'total' => 1,
            'next_page' => 'https://kodik-api.com/list?token=TOKEN&next=Y3Vyc29y',
            'results' => [],
        ]);

        $this->assertTrue($dto->hasNextPage());
    }

    public function test_from_array_handles_empty_results(): void
    {
        $dto = KodikListData::fromArray([
            'time' => '2ms',
            'total' => 0,
            'results' => [],
        ]);

        $this->assertSame('2ms', $dto->time);
        $this->assertSame(0, $dto->total);
        $this->assertSame([], $dto->results);
        $this->assertNull($dto->prevPage);
        $this->assertNull($dto->nextPage);
    }

    public function test_from_array_handles_missing_optional_fields(): void
    {
        $dto = KodikListData::fromArray([
            'results' => [],
        ]);

        $this->assertSame('', $dto->time);
        $this->assertSame(0, $dto->total);
        $this->assertSame([], $dto->results);
    }

    public function test_make_uses_defaults_for_every_field(): void
    {
        $dto = KodikListData::make();

        $this->assertSame('', $dto->time);
        $this->assertSame(0, $dto->total);
        $this->assertSame([], $dto->results);
        $this->assertNull($dto->prevPage);
        $this->assertNull($dto->nextPage);
    }
}
