<?php

declare(strict_types=1);

namespace App\Values\Concerns;

use InvalidArgumentException;
use Saloon\Http\Response;

/**
 * Reads a response body the API documents as a JSON object: anything else is a broken response,
 * and passing it on would surface as a TypeError deep inside a mapper instead of naming the fault.
 */
trait ReadsResponseBody
{
    /**
     * @return array<array-key, mixed>
     *
     * @throws InvalidArgumentException
     */
    private static function responseBody(Response $response): array
    {
        $body = $response->json();

        return is_array($body)
            ? $body
            : throw new InvalidArgumentException('API returned a response body that is not a JSON object.');
    }
}
