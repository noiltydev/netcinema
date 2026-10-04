<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik;

use Saloon\Http\Auth\QueryAuthenticator;
use Saloon\Http\Connector;

class KodikConnector extends Connector
{
    /**
     * @inheritDoc
     */
    public function resolveBaseUrl(): string
    {
        return config('noilty.services.kodik.endpoint');
    }

    protected function defaultAuth(): QueryAuthenticator
    {
        return new QueryAuthenticator('token', config('noilty.services.kodik.key'));
    }
}
