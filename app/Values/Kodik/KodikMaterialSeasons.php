<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikMaterialSeasons implements Arrayable
{
    /**
     * @param array<int, KodikMaterialSeason> $seasons
     */
    private function __construct(
        public array $seasons,
    ) {}

    /**
     * Season numbers arrive as JSON object keys, which PHP stores as integers.
     *
     * @param array<int, KodikMaterialSeason> $seasons
     */
    public static function make(array $seasons = []): self
    {
        return new self(seasons: $seasons);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $seasons = [];

        foreach ($data as $number => $season) {
            $seasons[(int) $number] = KodikMaterialSeason::fromArray((array) $season);
        }

        return self::make($seasons);
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return array_map(static fn (KodikMaterialSeason $season): array => $season->toArray(), $this->seasons);
    }
}
