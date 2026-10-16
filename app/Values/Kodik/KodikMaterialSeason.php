<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikMaterialSeason implements Arrayable
{
    /**
     * @param array<string, KodikMaterialEpisode> $episodes
     */
    private function __construct(
        public ?string $link,
        public array $episodes,
    ) {}

    /**
     * @param array<string, KodikMaterialEpisode> $episodes
     */
    public static function make(?string $link, array $episodes = []): self
    {
        return new self(link: $link, episodes: $episodes);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $episodes = [];

        foreach ((array) ($data['episodes'] ?? []) as $number => $episode) {
            $episodes[(string) $number] = KodikMaterialEpisode::fromArray($episode);
        }

        return self::make(link: isset($data['link']) ? (string) $data['link'] : null, episodes: $episodes);
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'link' => $this->link,
            'episodes' => array_map(
                static fn (KodikMaterialEpisode $episode): array => $episode->toArray(),
                $this->episodes,
            ),
        ];
    }
}
