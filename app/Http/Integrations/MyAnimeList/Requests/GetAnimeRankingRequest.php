<?php

declare(strict_types=1);

namespace App\Http\Integrations\MyAnimeList\Requests;

use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAnimeRankingRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * @param array<string, string|int|null> $filters
     *
     * @see \App\Values\MyAnimeList\MyAnimeListAnimeRankingFilters
     */
    public function __construct(
        protected readonly MyAnimeListRankingTypeAnimeFilter $rankingType,
        protected readonly array $filters = [],
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/v2/anime/ranking';
    }

    /**
     * @return array<string, string|int>
     */
    protected function defaultQuery(): array
    {
        $query = array_filter($this->filters, static fn ($value): bool => $value !== null && $value !== '');

        return [
            $this->rankingType::queryRankingTypeKey() => $this->rankingType->value,
            ...$query,
        ];
    }

    public function createDtoFromResponse(Response $response): MyAnimeListAnimeRankingData
    {
        return MyAnimeListAnimeRankingData::fromSaloonResponse($response);
    }
}
