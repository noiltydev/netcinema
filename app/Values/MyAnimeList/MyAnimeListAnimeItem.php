<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

class MyAnimeListAnimeItem implements Arrayable
{
    private function __construct(
        public int $id,
        public string $title,
        public MyAnimeListAnimeItemMainPicture $mainPicture,
    ) {}

    public static function make(int $id, string $title, MyAnimeListAnimeItemMainPicture $mainPicture): self
    {
        return new self(id: $id, title: $title, mainPicture: $mainPicture);
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            id: $data['id'],
            title: $data['title'],
            mainPicture: MyAnimeListAnimeItemMainPicture::fromArray($data['main_picture']),
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
