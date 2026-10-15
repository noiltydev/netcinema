<?php

declare(strict_types=1);

namespace App\Http\Integrations\MyAnimeList\Requests;

use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAnimeRankingRequest extends Request
{
    /** @see MyAnimeListRankingTypeAnimeFilter */
    private const string RANKING_TYPE_QUERY_KEY = 'ranking_type';

    /** @see MyAnimeListCommonFilter */
    private const string OFFSET_QUERY_KEY = 'offset';

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * @param array<string, string|int|null> $filters
     */
    public function __construct(
        protected readonly MyAnimeListRankingTypeAnimeFilter $rankingType,
        protected readonly array $filters = [],
        protected readonly ?int $offset = null,
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
        $query[self::RANKING_TYPE_QUERY_KEY] = $this->rankingType->value;

        if ($this->offset !== null) {
            $query[self::OFFSET_QUERY_KEY] = $this->offset;
        }

        return $query;
    }

    // @todo
//    public function createDtoFromResponse(Response $response): MyAnimeListRankingData
//    {
//        return MyAnimeListRankingData::fromSaloonResponse($response);
//    }
}
