<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Values\MyAnimeList\MyAnimeListAnimeFilters;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingData;
use App\Values\MyAnimeList\MyAnimeListCommonFilters;
use Saloon\Exceptions\Request\RequestException;

class MyAnimeListService
{
    public function __construct(
        private readonly MyAnimeListConnector $connector,
    ) {}

    /**
     * @throws RequestException
     */
    public function getAnimeRanking(
        MyAnimeListRankingTypeAnimeFilter $rankingType,
        ?MyAnimeListCommonFilters $filters = null,
        ?int $offset = null,
    ): MyAnimeListAnimeRankingData {
        $response = $this->connector->send(
            new GetAnimeRankingRequest($rankingType, ($filters ?? MyAnimeListAnimeFilters::make())->toQuery(), $offset),
        );

        return $response->dtoOrFail();
    }
}
