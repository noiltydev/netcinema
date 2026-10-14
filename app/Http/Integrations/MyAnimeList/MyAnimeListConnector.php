<?php

declare(strict_types=1);

namespace App\Http\Integrations\MyAnimeList;

use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class MyAnimeListConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    /**
     * @inheritDoc
     */
    public function resolveBaseUrl(): string
    {
        return config('noilty.services.myanimelist.endpoint');
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator(config('noilty.services.myanimelist.client_id'), 'X-MAL-CLIENT-ID');
    }
}
