<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;

/**
 * The anime payload every anime endpoint returns as a node.
 *
 * Only id and title are always present: the caller drives which fields come back, so a field it
 * never asked for is absent rather than null.
 *
 * @see docs/integrations/myanimelist/methods/anime-list.md
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListAnimeItem implements Arrayable
{
    use ReadsRequiredFields;

    private function __construct(
        public int $id,
        public string $title,
        public ?MyAnimeListMainPicture $mainPicture,
        public ?MyAnimeListAlternativeTitles $alternativeTitles,
    ) {}

    public static function make(
        int $id,
        string $title,
        ?MyAnimeListMainPicture $mainPicture = null,
        ?MyAnimeListAlternativeTitles $alternativeTitles = null,
    ): self {
        return new self(id: $id, title: $title, mainPicture: $mainPicture, alternativeTitles: $alternativeTitles);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return self::make(
            id: (int) self::required($data, 'id'),
            title: (string) self::required($data, 'title'),
            mainPicture: self::nested($data, 'main_picture', MyAnimeListMainPicture::fromArray(...)),
            alternativeTitles: self::nested($data, 'alternative_titles', MyAnimeListAlternativeTitles::fromArray(...)),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'main_picture' => $this->mainPicture?->toArray(),
            'alternative_titles' => $this->alternativeTitles?->toArray(),
        ];
    }

    /**
     * @template T
     *
     * @param array<array-key, mixed>            $data
     * @param callable(array<array-key, mixed>): T $map
     *
     * @return T|null
     */
    private static function nested(array $data, string $key, callable $map): mixed
    {
        return is_array($data[$key] ?? null) ? $map($data[$key]) : null;
    }
}
