<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Values\Concerns\ReadsRequiredFields;
use App\Values\Concerns\ReadsResponseBody;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use Saloon\Http\Response;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikTranslationsData implements Arrayable
{
    use ReadsRequiredFields;
    use ReadsResponseBody;

    /**
     * @param array<int, KodikTranslation> $results
     */
    private function __construct(
        public string $time,
        public int $total,
        public array $results,
    ) {}

    /**
     * @param array<int, KodikTranslation> $results
     */
    public static function make(string $time = '', int $total = 0, array $results = []): self
    {
        return new self(time: $time, total: $total, results: $results);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromSaloonResponse(Response $response): self
    {
        return self::fromArray(self::responseBody($response));
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        $results = array_map(KodikTranslation::fromArray(...), array_values(self::requiredArray($data, 'results')));

        return self::make(time: (string) ($data['time'] ?? ''), total: (int) ($data['total'] ?? 0), results: $results);
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'time' => $this->time,
            'total' => $this->total,
            'results' => array_map(static fn (KodikTranslation $item): array => $item->toArray(), $this->results),
        ];
    }
}
