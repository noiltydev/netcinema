<?php

declare(strict_types=1);

namespace App\Http\Integrations\MyAnimeList\Requests;

use App\Values\MyAnimeList\MyAnimeListAnimeDetails;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAnimeDetailsRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * @param array<string, string|int|null> $filters
     *
     * @see \App\Values\MyAnimeList\MyAnimeListAnimeDetailsFilters
     */
    public function __construct(
        protected readonly int $animeId,
        protected readonly array $filters = [],
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/v2/anime/' . $this->animeId;
    }

    /**
     * @return array<string, string|int>
     */
    protected function defaultQuery(): array
    {
        return array_filter($this->filters, static fn ($value): bool => $value !== null && $value !== '');
    }

    public function createDtoFromResponse(Response $response): MyAnimeListAnimeDetails
    {
        return MyAnimeListAnimeDetails::fromSaloonResponse($response);
    }
}
