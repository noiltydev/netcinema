<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetListRequest;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Values\Kodik\KodikFilters;
use App\Values\Kodik\KodikListData;
use App\Values\Kodik\KodikTranslationsData;
use Saloon\Exceptions\Request\RequestException;

class KodikService
{
    public function __construct(
        private readonly KodikConnector $connector,
    )
    {
    }

    /**
     * @throws RequestException
     */
    public function getTranslations(?KodikFilters $filters = null): KodikTranslationsData
    {
        $response = $this->connector->send(
            new GetTranslationsRequest(
                ($filters ?? KodikFilters::forTranslations())->toQuery(),
            ),
        );

        return $response->dtoOrFail();
    }

    /**
     * @throws RequestException
     */
    public function getList(?KodikFilters $filters = null, ?string $next = null): KodikListData
    {
        $response = $this->connector->send(
            new GetListRequest(
                ($filters ?? KodikFilters::forList())->toQuery(),
                $next,
            ),
        );

        return $response->dtoOrFail();
    }
}
