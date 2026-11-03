<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

/**
 * The titles an anime answers to besides its main one; every part is optional.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListAlternativeTitles implements Arrayable
{
    /**
     * @param list<string>|null $synonyms
     */
    private function __construct(
        public ?array $synonyms,
        public ?string $en,
        public ?string $ja,
    ) {}

    /**
     * @param list<string>|null $synonyms
     */
    public static function make(?array $synonyms = null, ?string $en = null, ?string $ja = null): self
    {
        return new self(synonyms: $synonyms, en: $en, ja: $ja);
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return self::make(
            synonyms: isset($data['synonyms']) && is_array($data['synonyms'])
                ? array_values(array_map(strval(...), $data['synonyms']))
                : null,
            en: isset($data['en']) ? (string) $data['en'] : null,
            ja: isset($data['ja']) ? (string) $data['ja'] : null,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'synonyms' => $this->synonyms,
            'en' => $this->en,
            'ja' => $this->ja,
        ];
    }
}
