<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Kodik\KodikCommonFilter;

/**
 * Filters accepted by the /translations/v2 endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetTranslationsRequest
 */
final readonly class KodikTranslationsFilters extends KodikCommonFilters
{
    public static function make(): self
    {
        return new self([], KodikCommonFilter::values());
    }
}
