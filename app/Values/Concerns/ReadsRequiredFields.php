<?php

declare(strict_types=1);

namespace App\Values\Concerns;

use InvalidArgumentException;

/**
 * Reads a field the API documents as always present: a payload that lacks it is malformed, and
 * defaulting it would turn a broken response into a plausible looking value.
 */
trait ReadsRequiredFields
{
    private static function required(array $data, string $key): mixed
    {
        return $data[$key] ?? throw new InvalidArgumentException(sprintf('Required field is missing: %s', $key));
    }

    /**
     * @return array<array-key, mixed>
     */
    private static function requiredArray(array $data, string $key): array
    {
        $value = self::required($data, $key);

        return is_array($value)
            ? $value
            : throw new InvalidArgumentException(sprintf('Field must be an object: %s', $key));
    }
}
