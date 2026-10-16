<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingData;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingFilters;
use Saloon\Exceptions\Request\RequestException;

class MyAnimeListService
{
    public function __construct(
        private readonly MyAnimeListConnector $connector,
    ) {}

    /**
     * Follow MyAnimeListPaging::nextOffset() for the following pages.
     *
     * @throws RequestException
     */
    public function getAnimeRanking(
        MyAnimeListRankingTypeAnimeFilter $rankingType,
        ?MyAnimeListAnimeRankingFilters $filters = null,
    ): MyAnimeListAnimeRankingData {
        $response = $this->connector->send(
            new GetAnimeRankingRequest($rankingType, ($filters ?? MyAnimeListAnimeRankingFilters::make())->toQuery()),
        );

        return $response->dtoOrFail();
    }
}
