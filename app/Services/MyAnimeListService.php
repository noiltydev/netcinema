<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MyAnimeList\MyAnimeListRankingType;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Values\MyAnimeList\MyAnimeListRankingData;
use App\Values\MyAnimeList\MyAnimeListRankingFilters;
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
        ?MyAnimeListRankingFilters $filters = null,
        MyAnimeListRankingType $rankingType = MyAnimeListRankingType::ALL,
        ?int $offset = null,
    ): MyAnimeListRankingData {
        $response = $this->connector->send(
            new GetAnimeRankingRequest(
                $rankingType,
                ($filters ?? MyAnimeListRankingFilters::make())->toQuery(),
                $offset,
            ),
        );

        return $response->dtoOrFail();
    }
}
