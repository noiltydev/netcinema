<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\Requests;

use App\Values\KodikTranslationsData as DTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTranslationsRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/translations/v2';
    }

    public function createDtoFromResponse(Response $response): DTO
    {
        return DTO::fromSaloonResponse($response);
    }
}
