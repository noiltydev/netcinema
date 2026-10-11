<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikFilter;

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
}
