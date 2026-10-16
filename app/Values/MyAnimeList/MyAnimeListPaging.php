<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Uri;

/**
 * The paging object every MyAnimeList list endpoint returns, whose links are null on the first and
 * the last page respectively.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListPaging implements Arrayable
{
    private const string OFFSET_QUERY_KEY = 'offset';

    private function __construct(
        public ?string $next,
        public ?string $previous,
    ) {}

    public static function make(?string $next = null, ?string $previous = null): self
    {
        return new self(next: $next, previous: $previous);
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            next: isset($data['next']) ? (string) $data['next'] : null,
            previous: isset($data['previous']) ? (string) $data['previous'] : null,
        );
    }

    public function hasNextPage(): bool
    {
        return $this->next !== null && $this->next !== '';
    }

    public function nextOffset(): ?int
    {
        if (!$this->hasNextPage()) {
            return null;
        }

        $offset = Uri::of((string) $this->next)->query()->get(self::OFFSET_QUERY_KEY);

        return is_numeric($offset) ? (int) $offset : null;
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'next' => $this->next,
            'previous' => $this->previous,
        ];
    }
}
