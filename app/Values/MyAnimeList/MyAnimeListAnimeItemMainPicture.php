<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\MyAnimeList\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListAnimeItemMainPicture implements Arrayable
{
    use ReadsRequiredFields;

    private function __construct(
        public string $medium,
        public ?string $large,
    ) {}

    public static function make(string $medium, ?string $large = null): self
    {
        return new self(medium: $medium, large: $large);
    }

    /**
     * MyAnimeList sends no main_picture at all for entries that have no artwork.
     */
    public static function fromArray(?array $data): ?self
    {
        if ($data === null) {
            return null;
        }

        return self::make(
            medium: (string) self::required($data, 'medium'),
            large: isset($data['large']) ? (string) $data['large'] : null,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'medium' => $this->medium,
            'large' => $this->large,
        ];
    }
}
