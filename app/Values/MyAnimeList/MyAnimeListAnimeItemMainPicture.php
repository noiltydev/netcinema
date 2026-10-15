<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

class MyAnimeListAnimeItemMainPicture implements Arrayable
{
    private function __construct(
        public string $medium,
        public string $large,
    ) {}

    public static function make(string $medium, string $large): self
    {
        return new self(medium: $medium, large: $large);
    }

    public static function fromArray(array $data): self
    {
        return self::make(medium: $data['medium'], large: $data['large']);
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
