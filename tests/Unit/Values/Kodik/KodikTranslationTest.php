<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikTranslationType;
use App\Values\Kodik\KodikTranslation;
use PHPUnit\Framework\TestCase;

class KodikTranslationTest extends TestCase
{
    public function test_from_array_maps_all_fields(): void
    {
        $data = [
            'id' => 735,
            'title' => '2x2',
            'count' => 26,
        ];

        $translation = KodikTranslation::fromArray($data);

        $this->assertSame(735, $translation->id);
        $this->assertSame('2x2', $translation->title);
        $this->assertSame(26, $translation->count);
    }

    public function test_from_array_casts_string_values(): void
    {
        $data = [
            'id' => '824',
            'title' => '3df voice',
            'count' => '16',
        ];

        $translation = KodikTranslation::fromArray($data);

        $this->assertSame(824, $translation->id);
        $this->assertSame('3df voice', $translation->title);
        $this->assertSame(16, $translation->count);
    }

    public function test_from_array_handles_zero_count(): void
    {
        $data = [
            'id' => 1,
            'title' => 'Test',
            'count' => 0,
        ];

        $translation = KodikTranslation::fromArray($data);

        $this->assertSame(0, $translation->count);
    }

    public function test_name_keeps_title_without_subtitles_suffix(): void
    {
        $translation = KodikTranslation::fromArray([
            'id' => 735,
            'title' => '2x2',
            'count' => 26,
        ]);

        $this->assertSame('2x2', $translation->name());
    }

    public function test_name_strips_subtitles_suffix(): void
    {
        $translation = KodikTranslation::fromArray([
            'id' => 735,
            'title' => 'Название.Subtitles',
            'count' => 26,
        ]);

        $this->assertSame('Название', $translation->name());
    }

    public function test_slug_transliterates_name(): void
    {
        $translation = KodikTranslation::fromArray([
            'id' => 824,
            'title' => 'Название.Subtitles',
            'count' => 16,
        ]);

        $this->assertSame('nazvanie', $translation->slug());
    }

    public function test_slug_is_empty_for_title_without_transliterable_characters(): void
    {
        $translation = KodikTranslation::fromArray([
            'id' => 1,
            'title' => '!!!',
            'count' => 1,
        ]);

        $this->assertSame('', $translation->slug());
    }

    public function test_type_is_voice_without_subtitles_suffix(): void
    {
        $translation = KodikTranslation::fromArray([
            'id' => 735,
            'title' => 'Название',
            'count' => 26,
        ]);

        $this->assertSame(KodikTranslationType::VOICE, $translation->type());
    }

    public function test_type_is_subtitles_with_subtitles_suffix(): void
    {
        $translation = KodikTranslation::fromArray([
            'id' => 735,
            'title' => 'Название.Subtitles',
            'count' => 26,
        ]);

        $this->assertSame(KodikTranslationType::SUBTITLES, $translation->type());
    }
}
