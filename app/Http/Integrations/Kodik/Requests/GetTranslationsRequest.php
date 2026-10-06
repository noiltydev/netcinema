<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\Requests;

use App\Values\Kodik\KodikTranslationsData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTranslationsRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * @param array<string, string|int> $filters
     */
    public function __construct(
        protected readonly array $filters = [],
    )
    {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/translations/v2';
    }

    /**
     * @return array<string, string|int>
     */
    protected function defaultQuery(): array
    {
        return array_filter($this->filters, static fn($value): bool => $value !== null && $value !== '');
    }

    public function createDtoFromResponse(Response $response): KodikTranslationsData
    {
        return KodikTranslationsData::fromSaloonResponse($response);
    }
}
