<?php

declare(strict_types=1);

namespace App\Http\Integrations\MyAnimeList\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetAnimeRankingRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct() {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/v2/anime/ranking';
    }
}
