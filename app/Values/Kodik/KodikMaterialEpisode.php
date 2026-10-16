<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;

/**
 * One series of a season, present in two shapes depending on the request: with_episodes returns
 * the player link on its own, with_episodes_data returns an object carrying the title and frames
 * alongside it.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikMaterialEpisode implements Arrayable
{
    /**
     * @param array<int, string> $screenshots
     */
    private function __construct(
        public ?string $link,
        public ?string $title,
        public array $screenshots,
    ) {}

    /**
     * @param array<int, string> $screenshots
     */
    public static function make(?string $link, ?string $title = null, array $screenshots = []): self
    {
        return new self(link: $link, title: $title, screenshots: $screenshots);
    }

    /**
     * @param array<array-key, mixed>|string $data
     */
    public static function fromArray(array|string $data): self
    {
        if (is_string($data)) {
            return self::make(link: $data);
        }

        return self::make(
            link: isset($data['link']) ? (string) $data['link'] : null,
            title: isset($data['title']) ? (string) $data['title'] : null,
            screenshots: self::stringList($data['screenshots'] ?? []),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'link' => $this->link,
            'title' => $this->title,
            'screenshots' => $this->screenshots,
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_map(static fn (mixed $item): string => (string) $item, $value));
    }
}
