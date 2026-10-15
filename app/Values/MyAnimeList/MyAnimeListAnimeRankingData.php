<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;
use Saloon\Http\Response;

class MyAnimeListAnimeRankingData implements Arrayable
{
    /**
     * @param array<array> $data
     * @param MyAnimeListPaging $paging
     */
    private function __construct(
        public array $data,
        public MyAnimeListPaging $paging,
    ) {}

    public static function make(array $data, MyAnimeListPaging $paging): self
    {
        return new self(data: $data, paging: $paging);
    }

    public static function fromSaloonResponse(Response $response): self
    {
        /** @var array $data */
        $data = $response->json();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $items = $data['data'];
        $paging = $data['paging'];

        return self::make(
            data: array_map(MyAnimeListAnimeNode::fromArray(...), $items ?? []),
            paging: MyAnimeListPaging::fromArray($paging),
        );
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
