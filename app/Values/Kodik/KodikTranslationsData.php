<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use Illuminate\Contracts\Support\Arrayable;
use Saloon\Http\Response;

final readonly class KodikTranslationsData implements Arrayable
{
    /**
     * @param array<int, KodikTranslation> $results
     */
    private function __construct(
        public string $time,
        public int $total,
        public array $results,
    )
    {
    }

    /**
     * @param array<int, KodikTranslation> $results
     */
    public static function make(string $time = '', int $total = 0, array $results = []): self
    {
        return new self(
            time: $time,
            total: $total,
            results: $results,
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
            static fn(array $item): KodikTranslation => KodikTranslation::fromArray($item),
            $data['results'] ?? [],
        );

        return self::make(
            time: (string)($data['time'] ?? ''),
            total: (int)($data['total'] ?? 0),
            results: $results,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'time' => $this->time,
            'total' => $this->total,
            'results' => array_map(
                static fn(KodikTranslation $item): array => $item->toArray(),
                $this->results,
            ),
        ];
    }
}
