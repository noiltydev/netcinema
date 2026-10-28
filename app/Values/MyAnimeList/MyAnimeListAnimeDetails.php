<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Values\Concerns\ReadsResponseBody;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use Saloon\Http\Response;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListAnimeDetails implements Arrayable
{
    use ReadsResponseBody;

    private function __construct(
        public MyAnimeListAnimeItem $anime,
    ) {}

    public static function make(MyAnimeListAnimeItem $anime): self
    {
        return new self(anime: $anime);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromSaloonResponse(Response $response): self
    {
        return self::fromArray(self::responseBody($response));
    }

    /**
     * A details endpoint answers with the bare node: no data envelope and no paging, unlike the
     * list endpoints. Fields the endpoint adds beyond the node are not mapped yet, so a payload
     * carrying them loses them.
     *
     * @param array<array-key, mixed> $data
     *
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        return self::make(anime: MyAnimeListAnimeItem::fromArray($data));
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return $this->anime->toArray();
    }
}
