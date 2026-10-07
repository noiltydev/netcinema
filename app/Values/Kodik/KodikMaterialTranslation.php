<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikTranslationType;
use Illuminate\Contracts\Support\Arrayable;

final readonly class KodikMaterialTranslation implements Arrayable
{
    private function __construct(
        public int $id,
        public string $title,
        public string $typeValue,
        public ?KodikTranslationType $type,
    )
    {
    }

    public static function make(
        int $id,
        string $title,
        ?KodikTranslationType $type = null,
        ?string $typeValue = null,
    ): self
    {
        $rawTypeValue = $typeValue ?? $type?->value ?? '';

        return new self(
            id: $id,
            title: $title,
            typeValue: $rawTypeValue,
            type: KodikTranslationType::tryFrom($rawTypeValue),
        );
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            id: (int)($data['id'] ?? 0),
            title: (string)($data['title'] ?? ''),
            typeValue: (string)($data['type'] ?? ''),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->typeValue,
        ];
    }
}
