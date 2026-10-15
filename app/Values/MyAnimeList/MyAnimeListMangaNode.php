<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

class MyAnimeListMangaNode implements Arrayable
{
    private function __construct() {}

    public static function make(): self
    {
        return new self();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
