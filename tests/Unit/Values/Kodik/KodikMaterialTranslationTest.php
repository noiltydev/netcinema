<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\Kodik\KodikTranslationType;
use App\Values\Kodik\KodikMaterialTranslation;
use PHPUnit\Framework\TestCase;

class KodikMaterialTranslationTest extends TestCase
{
    public function test_from_array_maps_all_fields(): void
    {
        $translation = KodikMaterialTranslation::fromArray([
            'id' => 611,
            'title' => 'ColdFilm',
            'type' => 'voice',
        ]);

        static::assertSame(611, $translation->id);
        static::assertSame('ColdFilm', $translation->title);
        static::assertSame(KodikTranslationType::VOICE, $translation->type);
        static::assertSame('voice', $translation->typeValue);
    }

    public function test_from_array_maps_subtitles_type(): void
    {
        $translation = KodikMaterialTranslation::fromArray([
            'id' => 735,
            'title' => '2x2',
            'type' => 'subtitles',
        ]);

        static::assertSame(KodikTranslationType::SUBTITLES, $translation->type);
    }

    public function test_from_array_keeps_undocumented_type_as_raw_value(): void
    {
        $translation = KodikMaterialTranslation::fromArray([
            'id' => 1,
            'title' => 'Unknown',
            'type' => 'original',
        ]);

        static::assertNull($translation->type);
        static::assertSame('original', $translation->typeValue);
    }

    public function test_from_array_handles_missing_fields(): void
    {
        $translation = KodikMaterialTranslation::fromArray([]);

        static::assertSame(0, $translation->id);
        static::assertSame('', $translation->title);
        static::assertNull($translation->type);
        static::assertSame('', $translation->typeValue);
    }

    public function test_make_derives_type_value_from_type(): void
    {
        $translation = KodikMaterialTranslation::make(611, 'ColdFilm', KodikTranslationType::VOICE);

        static::assertSame('voice', $translation->typeValue);
        static::assertSame(KodikTranslationType::VOICE, $translation->type);
    }

    public function test_to_array_serializes_type_as_string(): void
    {
        $translation = KodikMaterialTranslation::make(611, 'ColdFilm', KodikTranslationType::VOICE);

        static::assertSame(
            [
                'id' => 611,
                'title' => 'ColdFilm',
                'type' => 'voice',
            ],
            $translation->toArray(),
        );
    }
}
