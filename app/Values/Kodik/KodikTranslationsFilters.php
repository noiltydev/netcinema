<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikFilter;
use InvalidArgumentException;

/**
 * Filters accepted by the /translations/v2 endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetTranslationsRequest
 */
final readonly class KodikTranslationsFilters extends KodikFilters
{
    public static function make(): self
    {
        return new self([], KodikFilter::values());
    }

    /**
     * Escape hatch for filters without a dedicated method.
     *
     * @throws InvalidArgumentException
     */
    public function with(KodikFilter $key, string|int|bool $value): static
    {
        return $this->put($key, $value);
    }
}
