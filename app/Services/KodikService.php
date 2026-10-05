<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\KodikFilter;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Values\Kodik\KodikTranslationsData;
use InvalidArgumentException;
use Saloon\Exceptions\Request\RequestException;

class KodikService
{
    public function __construct(
        private readonly KodikConnector $connector,
    )
    {
    }

    /**
     * @param array<string, string|int> $filters
     *
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function getTranslations(array $filters = []): KodikTranslationsData
    {
        $this->validateFilters($filters);

        $response = $this->connector->send(
            new GetTranslationsRequest($filters)
        );

        return $response->dtoOrFail();
    }

    /**
     * @param array<string, string|int> $filters
     *
     * @throws InvalidArgumentException
     */
    private function validateFilters(array $filters): void
    {
        $allowed = KodikFilter::values();

        foreach (array_keys($filters) as $key) {
            throw_if(
                !in_array($key, $allowed, true),
                InvalidArgumentException::class,
                sprintf('Invalid filter key: %s', $key),
            );
        }
    }
}
