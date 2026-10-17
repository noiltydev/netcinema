<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeDetailsRequest;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Values\MyAnimeList\MyAnimeListAnimeDetails;
use App\Values\MyAnimeList\MyAnimeListAnimeDetailsFilters;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingData;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingFilters;
use InvalidArgumentException;
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

    /**
     * my_list_status comes back only for OAuth2 requests, so a client-id caller simply sees null.
     *
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function getAnimeDetails(
        int $animeId,
        ?MyAnimeListAnimeDetailsFilters $filters = null,
    ): MyAnimeListAnimeDetails {
        throw_if(
            $animeId < 1,
            InvalidArgumentException::class,
            sprintf('Anime id must be a positive integer, got %d.', $animeId),
        );

        $response = $this->connector->send(
            new GetAnimeDetailsRequest($animeId, ($filters ?? MyAnimeListAnimeDetailsFilters::make())->toQuery()),
        );

        return $response->dtoOrFail();
    }
}
