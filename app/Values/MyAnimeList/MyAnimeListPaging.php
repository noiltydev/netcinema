<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;

class MyAnimeListPaging implements Arrayable
{
    private function __construct(
        public string $next,
        public ?string $previous = null,
    ) {}

    public static function make(string $next, ?string $previous = null): self
    {
        return new self(next: $next, previous: $previous);
    }

    public static function fromArray(array $data): self
    {
        return self::make(next: $data['next'], previous: $data['previous'] ?? null);
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
