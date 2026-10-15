<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

class MyAnimeListAnimeNode implements Arrayable
{
    private function __construct(
        public MyAnimeListAnimeItem $node,
        public MyAnimeListRanking $ranking,
    ) {}

    public static function make(MyAnimeListAnimeItem $node, MyAnimeListRanking $ranking): self
    {
        return new self(node: $node, ranking: $ranking);
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            MyAnimeListAnimeItem::fromArray($data['node']),
            MyAnimeListRanking::fromArray($data['ranking']),
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
