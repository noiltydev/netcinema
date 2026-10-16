<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\Kodik\KodikMaterialType;
use App\Enums\Kodik\KodikTranslationType;
use App\Values\Kodik\KodikMaterial;
use App\Values\Kodik\KodikMaterialTranslation;
use PHPUnit\Framework\TestCase;

class KodikMaterialTest extends TestCase
{
    public function test_from_array_maps_core_fields(): void
    {
        $data = [
            'id' => 'movie-452654',
            'type' => 'foreign-movie',
            'link' => 'http://kodikplayer.com/video/19850/6476310cc6d90aa9304d5d8af3a91279/720p',
            'title' => 'Спортлото-82',
            'title_orig' => 'Спортлото-82',
            'year' => 1982,
            'kinopoisk_id' => '43949',
            'imdb_id' => 'tt0084716',
            'quality' => 'HDTVRip 720p',
            'created_at' => '2017-12-03T09:12:49Z',
            'updated_at' => '2018-04-10T08:25:23Z',
            'screenshots' => [
                'https://i.kodikres.com/screenshots/video/50811/1.jpg',
                'https://i.kodikres.com/screenshots/video/50811/2.jpg',
            ],
        ];

        $material = KodikMaterial::fromArray($data);

        static::assertSame('movie-452654', $material->id);
        static::assertSame('Спортлото-82', $material->title);
        static::assertSame(KodikMaterialType::FOREIGN_MOVIE, $material->type);
        static::assertSame('foreign-movie', $material->typeValue);
        static::assertSame('http://kodikplayer.com/video/19850/6476310cc6d90aa9304d5d8af3a91279/720p', $material->link);
        static::assertSame('Спортлото-82', $material->titleOrig);
        static::assertSame(1982, $material->year);
        static::assertSame('43949', $material->kinopoiskId);
        static::assertSame('tt0084716', $material->imdbId);
        static::assertSame('HDTVRip 720p', $material->quality);
        static::assertCount(2, $material->screenshots);
    }

    public function test_from_array_maps_translation_object(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'serial-452654',
            'type' => 'anime',
            'title' => 'Игра престолов',
            'translation' => [
                'id' => 611,
                'title' => 'ColdFilm',
                'type' => 'voice',
            ],
        ]);

        static::assertInstanceOf(KodikMaterialTranslation::class, $material->translation);
        static::assertSame(611, $material->translation->id);
        static::assertSame('ColdFilm', $material->translation->title);
        static::assertSame(KodikTranslationType::VOICE, $material->translation->type);
    }

    public function test_from_array_maps_boolean_and_list_fields(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'serial-452654',
            'title' => 'Игра престолов',
            'camrip' => true,
            'lgbt' => false,
            'blocked_countries' => ['RU'],
        ]);

        static::assertTrue($material->camrip);
        static::assertFalse($material->lgbt);
        static::assertSame(['RU'], $material->blockedCountries);
        static::assertSame([], $material->screenshots);
    }

    public function test_from_array_parses_timestamps(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'title' => 'Спортлото-82',
            'created_at' => '2017-12-03T09:12:49Z',
            'updated_at' => '2018-04-10T08:25:23Z',
        ]);

        static::assertSame('2017-12-03T09:12:49+00:00', $material->createdAt?->toIso8601String());
        static::assertSame('2018-04-10T08:25:23+00:00', $material->updatedAt?->toIso8601String());
    }

    public function test_from_array_handles_missing_optional_fields(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'title' => 'Спортлото-82',
        ]);

        static::assertNull($material->type);
        static::assertSame('', $material->typeValue);
        static::assertNull($material->link);
        static::assertNull($material->titleOrig);
        static::assertNull($material->otherTitle);
        static::assertNull($material->translation);
        static::assertNull($material->year);
        static::assertNull($material->camrip);
        static::assertNull($material->createdAt);
        static::assertSame([], $material->blockedCountries);
        static::assertSame([], $material->screenshots);
    }

    public function test_from_array_keeps_undocumented_type_as_raw_value(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'type' => 'documentary-film',
            'title' => 'Неизвестный тип',
        ]);

        static::assertNull($material->type);
        static::assertSame('documentary-film', $material->typeValue);
    }

    public function test_make_derives_type_value_from_type(): void
    {
        $material = KodikMaterial::make(
            id: 'serial-452654',
            title: 'Игра престолов',
            type: KodikMaterialType::ANIME_SERIAL,
        );

        static::assertSame('anime-serial', $material->typeValue);
        static::assertSame(KodikMaterialType::ANIME_SERIAL, $material->type);
        static::assertNull($material->createdAt);
    }

    public function test_to_array_round_trips_through_from_array(): void
    {
        $data = [
            'id' => 'movie-452654',
            'type' => 'foreign-movie',
            'title' => 'Спортлото-82',
            'year' => 1982,
            'translation' => [
                'id' => 703,
                'title' => 'Не требуется',
                'type' => 'voice',
            ],
        ];

        $material = KodikMaterial::fromArray($data);

        static::assertSame($data['id'], $material->toArray()['id']);
        static::assertSame($data['type'], $material->toArray()['type']);
        static::assertSame($data['title'], $material->toArray()['title']);
        static::assertSame($data['year'], $material->toArray()['year']);
        static::assertSame($data['translation'], $material->toArray()['translation']);
    }
}
