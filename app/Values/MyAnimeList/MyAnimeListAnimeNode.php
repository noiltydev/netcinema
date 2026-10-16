<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;

/**
 * One entry of a ranking response: the anime node plus the rank it holds.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListAnimeNode implements Arrayable
{
    use ReadsRequiredFields;

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
            node: MyAnimeListAnimeItem::fromArray(self::requiredArray($data, 'node')),
            ranking: MyAnimeListRanking::fromArray(self::requiredArray($data, 'ranking')),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'node' => $this->node->toArray(),
            'ranking' => $this->ranking->toArray(),
        ];
    }
}
