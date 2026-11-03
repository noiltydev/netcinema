<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Values\MyAnimeList\MyAnimeListAlternativeTitles;
use App\Values\MyAnimeList\MyAnimeListAnimeItem;
use App\Values\MyAnimeList\MyAnimeListMainPicture;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeItemTest extends TestCase
{
    public function test_from_array_maps_the_documented_fields(): void
    {
        $item = MyAnimeListAnimeItem::fromArray([
            'id' => 30_230,
            'title' => 'One Piece',
            'main_picture' => [
                'medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
            ],
            'alternative_titles' => [
                'synonyms' => ['One Piece', 'ワンピース'],
                'en' => 'One Piece',
                'ja' => 'ワンピース',
            ],
        ]);

        static::assertSame(30_230, $item->id);
        static::assertSame('One Piece', $item->title);
        static::assertNotNull($item->mainPicture);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245.jpg', $item->mainPicture->medium);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245l.jpg', $item->mainPicture->large);
        static::assertSame(['One Piece', 'ワンピース'], $item->alternativeTitles->synonyms);
        static::assertSame('One Piece', $item->alternativeTitles->en);
        static::assertSame('ワンピース', $item->alternativeTitles->ja);
    }

    public function test_from_array_leaves_unrequested_fields_empty(): void
    {
        $item = MyAnimeListAnimeItem::fromArray(['id' => 1, 'title' => 'Bare']);

        static::assertNull($item->mainPicture);
        static::assertNull($item->alternativeTitles);
    }

    public function test_from_array_maps_partial_alternative_titles(): void
    {
        $item = MyAnimeListAnimeItem::fromArray([
            'id' => 1,
            'title' => 'Japanese only',
            'alternative_titles' => ['ja' => 'ワンピース'],
        ]);

        static::assertNull($item->alternativeTitles?->synonyms);
        static::assertNull($item->alternativeTitles?->en);
        static::assertSame('ワンピース', $item->alternativeTitles->ja);
    }

    public function test_from_array_keeps_a_missing_large_picture_null(): void
    {
        $item = MyAnimeListAnimeItem::fromArray([
            'id' => 1,
            'title' => 'Medium only',
            'main_picture' => ['medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg'],
        ]);

        static::assertNotNull($item->mainPicture);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245.jpg', $item->mainPicture->medium);
        static::assertNull($item->mainPicture->large);
    }

    public function test_from_array_maps_a_null_picture_to_null(): void
    {
        $item = MyAnimeListAnimeItem::fromArray(['id' => 1, 'title' => 'Explicit null', 'main_picture' => null]);

        static::assertNull($item->mainPicture);
    }

    public function test_to_array_returns_the_api_shape(): void
    {
        static::assertSame(
            [
                'id' => 1,
                'title' => 'One Piece',
                'main_picture' => ['medium' => 'https://medium', 'large' => 'https://large'],
                'alternative_titles' => ['synonyms' => null, 'en' => null, 'ja' => 'ワンピース'],
            ],
            MyAnimeListAnimeItem::make(
                id: 1,
                title: 'One Piece',
                mainPicture: MyAnimeListMainPicture::make('https://medium', 'https://large'),
                alternativeTitles: MyAnimeListAlternativeTitles::make(ja: 'ワンピース'),
            )->toArray(),
        );
    }

    public function test_it_round_trips_through_to_array(): void
    {
        $item = MyAnimeListAnimeItem::fromArray([
            'id' => 1,
            'title' => 'One Piece',
            'main_picture' => ['medium' => 'https://m.jpg'],
            'alternative_titles' => ['synonyms' => ['One Piece'], 'en' => 'One Piece'],
        ]);

        static::assertSame($item->toArray(), MyAnimeListAnimeItem::fromArray($item->toArray())->toArray());
    }

    public function test_picture_serializes_to_array(): void
    {
        static::assertSame(
            ['medium' => 'https://medium', 'large' => null],
            MyAnimeListMainPicture::make('https://medium')->toArray(),
        );
    }

    public function test_from_array_rejects_a_node_without_a_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: title');

        MyAnimeListAnimeItem::fromArray(['id' => 1]);
    }

    public function test_picture_requires_a_medium_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: medium');

        MyAnimeListMainPicture::fromArray(['large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg']);
    }
}
