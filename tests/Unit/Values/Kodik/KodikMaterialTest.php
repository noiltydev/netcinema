<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikMaterialType;
use App\Enums\KodikTranslationType;
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

        $this->assertSame('movie-452654', $material->id);
        $this->assertSame('Спортлото-82', $material->title);
        $this->assertSame(KodikMaterialType::FOREIGN_MOVIE, $material->type);
        $this->assertSame('foreign-movie', $material->typeValue);
        $this->assertSame('http://kodikplayer.com/video/19850/6476310cc6d90aa9304d5d8af3a91279/720p', $material->link);
        $this->assertSame('Спортлото-82', $material->titleOrig);
        $this->assertSame(1982, $material->year);
        $this->assertSame('43949', $material->kinopoiskId);
        $this->assertSame('tt0084716', $material->imdbId);
        $this->assertSame('HDTVRip 720p', $material->quality);
        $this->assertCount(2, $material->screenshots);
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

        $this->assertInstanceOf(KodikMaterialTranslation::class, $material->translation);
        $this->assertSame(611, $material->translation->id);
        $this->assertSame('ColdFilm', $material->translation->title);
        $this->assertSame(KodikTranslationType::VOICE, $material->translation->type);
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

        $this->assertTrue($material->camrip);
        $this->assertFalse($material->lgbt);
        $this->assertSame(['RU'], $material->blockedCountries);
        $this->assertSame([], $material->screenshots);
    }

    public function test_from_array_parses_timestamps(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'title' => 'Спортлото-82',
            'created_at' => '2017-12-03T09:12:49Z',
            'updated_at' => '2018-04-10T08:25:23Z',
        ]);

        $this->assertSame('2017-12-03T09:12:49+00:00', $material->createdAt?->toIso8601String());
        $this->assertSame('2018-04-10T08:25:23+00:00', $material->updatedAt?->toIso8601String());
    }

    public function test_from_array_handles_missing_optional_fields(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'title' => 'Спортлото-82',
        ]);

        $this->assertNull($material->type);
        $this->assertSame('', $material->typeValue);
        $this->assertNull($material->link);
        $this->assertNull($material->titleOrig);
        $this->assertNull($material->otherTitle);
        $this->assertNull($material->translation);
        $this->assertNull($material->year);
        $this->assertNull($material->camrip);
        $this->assertNull($material->createdAt);
        $this->assertSame([], $material->blockedCountries);
        $this->assertSame([], $material->screenshots);
    }

    public function test_from_array_keeps_undocumented_type_as_raw_value(): void
    {
        $material = KodikMaterial::fromArray([
            'id' => 'movie-452654',
            'type' => 'documentary-film',
            'title' => 'Неизвестный тип',
        ]);

        $this->assertNull($material->type);
        $this->assertSame('documentary-film', $material->typeValue);
    }

    public function test_make_derives_type_value_from_type(): void
    {
        $material = KodikMaterial::make(
            id: 'serial-452654',
            title: 'Игра престолов',
            type: KodikMaterialType::ANIME_SERIAL,
        );

        $this->assertSame('anime-serial', $material->typeValue);
        $this->assertSame(KodikMaterialType::ANIME_SERIAL, $material->type);
        $this->assertNull($material->createdAt);
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

        $this->assertSame($data['id'], $material->toArray()['id']);
        $this->assertSame($data['type'], $material->toArray()['type']);
        $this->assertSame($data['title'], $material->toArray()['title']);
        $this->assertSame($data['year'], $material->toArray()['year']);
        $this->assertSame($data['translation'], $material->toArray()['translation']);
    }
}
