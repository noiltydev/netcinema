<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;

final readonly class KodikTranslation implements Arrayable
{
    private function __construct(
        public int $id,
        public string $title,
        public int $count,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            title: (string)$data['title'],
            count: (int)$data['count'],
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'count' => $this->count,
        ];
    }
}
