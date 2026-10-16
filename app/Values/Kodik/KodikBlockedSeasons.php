<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;

/**
 * The seasons of a serial that the API has blocked: either the whole serial, as the string "all",
 * or per season either "all" again or the numbers of the blocked series.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikBlockedSeasons implements Arrayable
{
    private const string ALL_MARKER = 'all';

    /**
     * @param array<string, list<string>> $series blocked series per season, empty where whole seasons are blocked
     */
    private function __construct(
        public bool $all,
        public array $series,
    ) {}

    /**
     * @param array<string, list<string>> $series
     */
    public static function make(bool $all, array $series = []): self
    {
        return new self(all: $all, series: $series);
    }

    /**
     * @param array<array-key, mixed>|string $data
     */
    public static function fromArray(mixed $data): self
    {
        if (is_string($data)) {
            return self::make(all: $data === self::ALL_MARKER);
        }

        $series = [];

        foreach ((array) $data as $number => $blocked) {
            if (is_array($blocked)) {
                $series[(string) $number] = array_values(array_map(
                    static fn (mixed $episode): string => (string) $episode,
                    $blocked,
                ));
            }
        }

        return self::make(all: false, series: $series);
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'all' => $this->all,
            'series' => $this->series,
        ];
    }
}
