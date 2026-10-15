<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Values\MyAnimeList\MyAnimeListAnimeItem;
use App\Values\MyAnimeList\MyAnimeListAnimeItemMainPicture;
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
        ]);

        static::assertSame(30_230, $item->id);
        static::assertSame('One Piece', $item->title);
        static::assertNotNull($item->mainPicture);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245.jpg', $item->mainPicture->medium);
        static::assertSame('https://cdn.myanimelist.net/images/anime/6/73245l.jpg', $item->mainPicture->large);
    }

    public function test_from_array_keeps_a_missing_picture_null(): void
    {
        $item = MyAnimeListAnimeItem::fromArray(['id' => 1, 'title' => 'No artwork']);

        static::assertNull($item->mainPicture);
        static::assertSame(['id' => 1, 'title' => 'No artwork', 'main_picture' => null], $item->toArray());
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

    public function test_main_picture_serializes_to_array(): void
    {
        static::assertSame(
            ['medium' => 'https://medium', 'large' => null],
            MyAnimeListAnimeItemMainPicture::make('https://medium')->toArray(),
        );
    }

    public function test_from_array_rejects_a_node_without_a_title(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: title');

        MyAnimeListAnimeItem::fromArray(['id' => 1]);
    }

    public function test_main_picture_requires_a_medium_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: medium');

        MyAnimeListAnimeItemMainPicture::fromArray([
            'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
        ]);
    }
}
