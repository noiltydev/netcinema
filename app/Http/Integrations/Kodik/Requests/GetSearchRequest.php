<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\Requests;

use App\Values\Kodik\KodikSearchData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSearchRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * @param array<string, string|int|null> $filters
     */
    public function __construct(
        protected readonly array $filters = [],
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/search';
    }

    /**
     * @return array<string, string|int>
     */
    protected function defaultQuery(): array
    {
        return array_filter($this->filters, static fn ($value): bool => $value !== null && $value !== '');
    }

    public function createDtoFromResponse(Response $response): KodikSearchData
    {
        return KodikSearchData::fromSaloonResponse($response);
    }
}
