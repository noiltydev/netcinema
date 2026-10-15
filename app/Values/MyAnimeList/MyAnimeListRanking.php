<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

class MyAnimeListRanking implements Arrayable
{
    private function __construct(
        public int $rank,
        public ?int $previousRank = null,
    ) {}

    public static function make(int $rank, ?int $previousRank = null): self
    {
        return new self(rank: $rank, previousRank: $previousRank);
    }

    public static function fromArray(array $data): self
    {
        return self::make(rank: $data['rank'], previousRank: $data['previous_rank'] ?? null);
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
