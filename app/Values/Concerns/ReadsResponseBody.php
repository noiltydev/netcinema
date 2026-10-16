<?php

declare(strict_types=1);

namespace App\Values\Concerns;

use InvalidArgumentException;
use JsonException;
use Saloon\Http\Response;

/**
 * Reads a response body the API documents as a JSON object: a body that is not JSON, or is JSON
 * but not an object, is a broken response. Failing here names the fault instead of surfacing it as
 * a JsonException or a TypeError deep inside a mapper.
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
        try {
            $body = $response->json();
        } catch (JsonException $jsonException) {
            throw new InvalidArgumentException('API returned a body that is not valid JSON.', previous: $jsonException);
        }

        // Saloon documents json() as always returning an array, but it decodes a JSON scalar body
        // to a scalar, so the guard is reachable.
        // @phpstan-ignore function.alreadyNarrowedType
        return is_array($body)
            ? $body
            : throw new InvalidArgumentException('API returned a response body that is not a JSON object.');
    }
}
