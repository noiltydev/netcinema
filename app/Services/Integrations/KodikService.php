<?php

declare(strict_types=1);

namespace App\Services\Integrations;

use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetListRequest;
use App\Http\Integrations\Kodik\Requests\GetSearchRequest;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Values\Kodik\KodikListData;
use App\Values\Kodik\KodikListFilters;
use App\Values\Kodik\KodikSearchData;
use App\Values\Kodik\KodikSearchFilters;
use App\Values\Kodik\KodikTranslationsData;
use App\Values\Kodik\KodikTranslationsFilters;
use Saloon\Exceptions\Request\RequestException;

class KodikService
{
    public function __construct(
        private readonly KodikConnector $connector,
    ) {}

    /**
     * @throws RequestException
     */
    public function getTranslations(?KodikTranslationsFilters $filters = null): KodikTranslationsData
    {
        $response = $this->connector->send(
            new GetTranslationsRequest(($filters ?? KodikTranslationsFilters::make())->toQuery()),
        );

        return $response->dtoOrFail();
    }

    /**
     * @throws RequestException
     */
    public function getList(?KodikListFilters $filters = null, ?string $next = null): KodikListData
    {
        $response = $this->connector->send(
            new GetListRequest(($filters ?? KodikListFilters::make())->toQuery(), $next),
        );

        return $response->dtoOrFail();
    }

    /**
     * @throws RequestException
     */
    public function search(?KodikSearchFilters $filters = null): KodikSearchData
    {
        $response = $this->connector->send(new GetSearchRequest(($filters ?? KodikSearchFilters::make())->toQuery()));

        return $response->dtoOrFail();
    }
}
