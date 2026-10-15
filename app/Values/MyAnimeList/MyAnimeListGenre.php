<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

final readonly class MyAnimeListGenre implements Arrayable
{
    private function __construct(
        public int $id,
        public string $name,
    ) {}

    public static function make(int $id, string $name): self
    {
        return new self(id: $id, name: $name);
    }

    public static function fromArray(array $data): self
    {
        return self::make(id: (int) ($data['id'] ?? 0), name: (string) ($data['name'] ?? ''));
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
