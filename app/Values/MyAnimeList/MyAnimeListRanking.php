<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\MyAnimeList\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;

/**
 * The ranking block the ranking endpoints add to every node they return.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListRanking implements Arrayable
{
    use ReadsRequiredFields;

    private function __construct(
        public int $rank,
        public ?int $previousRank,
    ) {}

    /**
     * MyAnimeList declares previous_rank in the schema but does not send it, so it stays null.
     */
    public static function make(int $rank, ?int $previousRank = null): self
    {
        return new self(rank: $rank, previousRank: $previousRank);
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            rank: (int) self::required($data, 'rank'),
            previousRank: isset($data['previous_rank']) ? (int) $data['previous_rank'] : null,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'rank' => $this->rank,
            'previous_rank' => $this->previousRank,
        ];
    }
}
