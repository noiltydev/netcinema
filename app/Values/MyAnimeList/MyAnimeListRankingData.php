<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Uri;
use Saloon\Http\Response;

/**
 * One page of /anime/ranking.
 *
 * MyAnimeList paginates by offset and returns the next and previous pages as ready-made
 * links; those links are the authority on where the next page starts, so the offsets are
 * read back out of them rather than guessed from the page size.
 */
final readonly class MyAnimeListRankingData implements Arrayable
{
    private const string OFFSET_QUERY_KEY = 'offset';

    /**
     * @param array<int, MyAnimeListRankingEntry> $entries
     */
    private function __construct(
        public array $entries,
        public ?string $prevPage,
        public ?string $nextPage,
    ) {}

    /**
     * @param array<int, MyAnimeListRankingEntry> $entries
     */
    public static function make(array $entries = [], ?string $prevPage = null, ?string $nextPage = null): self
    {
        return new self(entries: $entries, prevPage: $prevPage, nextPage: $nextPage);
    }

    public static function fromSaloonResponse(Response $response): self
    {
        /** @var array $data */
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $entries = array_map(MyAnimeListRankingEntry::fromArray(...), self::objectList($data['data'] ?? []));
        $paging = is_array($data['paging'] ?? null) ? $data['paging'] : [];

        return self::make(
            entries: $entries,
            prevPage: self::nullableString($paging['previous'] ?? null),
            nextPage: self::nullableString($paging['next'] ?? null),
        );
    }

    public function nextOffset(): ?int
    {
        return self::offsetOf($this->nextPage);
    }

    public function prevOffset(): ?int
    {
        return self::offsetOf($this->prevPage);
    }

    public function hasNextPage(): bool
    {
        return $this->nextOffset() !== null;
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'data' => array_map(static fn (MyAnimeListRankingEntry $entry): array => $entry->toArray(), $this->entries),
            'paging' => [
                'previous' => $this->prevPage,
                'next' => $this->nextPage,
            ],
        ];
    }

    private static function offsetOf(?string $pageUrl): ?int
    {
        if ($pageUrl === null || $pageUrl === '') {
            return null;
        }

        $offset = Uri::of($pageUrl)->query()->get(self::OFFSET_QUERY_KEY);

        return is_numeric($offset) ? (int) $offset : null;
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null ? null : (string) $value;
    }

    /**
     * @return array<int, array>
     */
    private static function objectList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, 'is_array'));
    }
}
