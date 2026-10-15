<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\MyAnimeList\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;

/**
 * The anime payload every anime endpoint returns as a node.
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
        public ?MyAnimeListAnimeItemMainPicture $mainPicture,
    ) {}

    public static function make(int $id, string $title, ?MyAnimeListAnimeItemMainPicture $mainPicture = null): self
    {
        return new self(id: $id, title: $title, mainPicture: $mainPicture);
    }

    /**
     * MyAnimeList sends no main_picture at all for entries that have no artwork.
     */
    public static function fromArray(array $data): self
    {
        return self::make(
            id: (int) self::required($data, 'id'),
            title: (string) self::required($data, 'title'),
            mainPicture: self::mainPictureFrom($data),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'main_picture' => $this->mainPicture?->toArray(),
        ];
    }

    private static function mainPictureFrom(array $data): ?MyAnimeListAnimeItemMainPicture
    {
        if (($data['main_picture'] ?? null) === null) {
            return null;
        }

        return MyAnimeListAnimeItemMainPicture::fromArray(self::requiredArray($data, 'main_picture'));
    }
}
