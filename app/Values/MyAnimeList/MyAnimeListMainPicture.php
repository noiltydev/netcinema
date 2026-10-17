<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;

/**
 * One artwork entry, whether it is the node's main_picture or one of its pictures.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListMainPicture implements Arrayable
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
     * A node without artwork carries no picture at all, which the reader reports as an absent key
     * rather than as a null picture.
     *
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
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
