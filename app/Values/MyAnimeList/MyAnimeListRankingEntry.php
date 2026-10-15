<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

/**
 * One entry of a ranking page: the anime plus where it placed.
 *
 * previousRank is declared by MyAnimeList but has never appeared in a response, so it is
 * nullable and normally null.
 */
final readonly class MyAnimeListRankingEntry implements Arrayable
{
    private function __construct(
        public MyAnimeListAnime $anime,
        public ?int $rank,
        public ?int $previousRank,
    ) {}

    public static function make(MyAnimeListAnime $anime, ?int $rank = null, ?int $previousRank = null): self
    {
        return new self(anime: $anime, rank: $rank, previousRank: $previousRank);
    }

    public static function fromArray(array $data): self
    {
        $ranking = is_array($data['ranking'] ?? null) ? $data['ranking'] : [];
        $node = is_array($data['node'] ?? null) ? $data['node'] : [];

        return self::make(
            anime: MyAnimeListAnime::fromArray($node),
            rank: isset($ranking['rank']) ? (int) $ranking['rank'] : null,
            previousRank: isset($ranking['previous_rank']) ? (int) $ranking['previous_rank'] : null,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'node' => $this->anime->toArray(),
            'ranking' => [
                'rank' => $this->rank,
                'previous_rank' => $this->previousRank,
            ],
        ];
    }
}
