<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetListRequest;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Values\Kodik\KodikListData;
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
        $this->validateFilters($filters, KodikFilter::values());

        $response = $this->connector->send(
            new GetTranslationsRequest($filters),
        );

        return $response->dtoOrFail();
    }

    /**
     * @param array<string, string|int> $filters
     *
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function getList(array $filters = [], ?string $next = null): KodikListData
    {
        $this->validateFilters($filters, array_merge(
            KodikFilter::values(),
            KodikListFilter::values(),
        ));

        $response = $this->connector->send(
            new GetListRequest($filters, $next),
        );

        return $response->dtoOrFail();
    }

    /**
     * @param array<string, string|int> $filters
     * @param array<string> $allowedKeys
     *
     * @throws InvalidArgumentException
     */
    private function validateFilters(array $filters, array $allowedKeys): void
    {
        foreach (array_keys($filters) as $key) {
            throw_if(
                !in_array($key, $allowedKeys, true),
                InvalidArgumentException::class,
                sprintf('Invalid filter key: %s', $key),
            );
        }
    }
}
