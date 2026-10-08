<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\Requests;

use App\Values\Kodik\KodikListData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetListRequest extends Request
{
    private const string CURSOR_QUERY_KEY = 'next';

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * @param array<string, string|int> $filters
     */
    public function __construct(
        protected readonly array $filters = [],
        protected readonly ?string $next = null,
    )
    {
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/list';
    }

    /**
     * @return array<string, string|int>
     */
    protected function defaultQuery(): array
    {
        $query = array_filter($this->filters, static fn($value): bool => $value !== null && $value !== '');

        if ($this->next !== null && $this->next !== '') {
            $query[self::CURSOR_QUERY_KEY] = $this->next;
        }

        return $query;
    }

    public function createDtoFromResponse(Response $response): KodikListData
    {
        return KodikListData::fromSaloonResponse($response);
    }
}
