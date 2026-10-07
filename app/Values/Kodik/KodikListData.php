<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Uri;
use Saloon\Http\Response;

final readonly class KodikListData implements Arrayable
{
    private const string CURSOR_QUERY_KEY = 'next';

    /**
     * @param array<int, KodikMaterial> $results
     */
    private function __construct(
        public string $time,
        public int $total,
        public array $results,
        public ?string $prevPage,
        public ?string $nextPage,
    )
    {
    }

    /**
     * @param array<int, KodikMaterial> $results
     */
    public static function make(
        string $time = '',
        int $total = 0,
        array $results = [],
        ?string $prevPage = null,
        ?string $nextPage = null,
    ): self
    {
        return new self(
            time: $time,
            total: $total,
            results: $results,
            prevPage: $prevPage,
            nextPage: $nextPage,
        );
    }

    public static function fromSaloonResponse(Response $response): self
    {
        /** @var array $data */
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $results = array_map(
            static fn(array $item): KodikMaterial => KodikMaterial::fromArray($item),
            $data['results'] ?? [],
        );

        return self::make(
            time: (string)($data['time'] ?? ''),
            total: (int)($data['total'] ?? 0),
            results: $results,
            prevPage: $data['prev_page'] ?? null,
            nextPage: $data['next_page'] ?? null,
        );
    }

    public function nextCursor(): ?string
    {
        return $this->cursorOf($this->nextPage);
    }

    public function prevCursor(): ?string
    {
        return $this->cursorOf($this->prevPage);
    }

    public function hasNextPage(): bool
    {
        return $this->nextCursor() !== null;
    }

    /** {@inheritdoc} */
    public function toArray(): array
    {
        return [
            'time' => $this->time,
            'total' => $this->total,
            'results' => array_map(
                static fn(KodikMaterial $item): array => $item->toArray(),
                $this->results,
            ),
            'prev_page' => $this->prevPage,
            'next_page' => $this->nextPage,
        ];
    }

    private function cursorOf(?string $pageUrl): ?string
    {
        if ($pageUrl === null || $pageUrl === '') {
            return null;
        }

        $cursor = Uri::of($pageUrl)->query()->get(self::CURSOR_QUERY_KEY);

        return is_string($cursor) && $cursor !== '' ? $cursor : null;
    }
}
