<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\MyAnimeList\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use Saloon\Http\Response;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListAnimeRankingData implements Arrayable
{
    use ReadsRequiredFields;

    /**
     * @param list<MyAnimeListAnimeNode> $data
     */
    private function __construct(
        public array $data,
        public MyAnimeListPaging $paging,
    ) {}

    /**
     * @param list<MyAnimeListAnimeNode> $data
     */
    public static function make(array $data, MyAnimeListPaging $paging): self
    {
        return new self(data: $data, paging: $paging);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromSaloonResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * MyAnimeList wraps every list response in data and paging; a payload without either is
     * malformed rather than empty.
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $nodes = array_map(MyAnimeListAnimeNode::fromArray(...), array_values(self::requiredArray($data, 'data')));

        return self::make(data: $nodes, paging: MyAnimeListPaging::fromArray(self::requiredArray($data, 'paging')));
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'data' => array_map(static fn (MyAnimeListAnimeNode $entry): array => $entry->toArray(), $this->data),
            'paging' => $this->paging->toArray(),
        ];
    }
}
