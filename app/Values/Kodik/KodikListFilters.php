<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Values\Kodik\Concerns\HasLimit;
use App\Values\Kodik\Concerns\HasTranslationId;

/**
 * Filters accepted by the /list endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetListRequest
 */
final readonly class KodikListFilters extends KodikFilters
{
    use HasLimit;
    use HasTranslationId;

    public static function make(): self
    {
        return new self([], array_merge(KodikFilter::values(), KodikListFilter::values()));
    }
}
