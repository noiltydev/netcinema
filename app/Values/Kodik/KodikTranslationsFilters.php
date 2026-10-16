<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Kodik\KodikAllOfFilter;
use App\Enums\Kodik\KodikCatalogFilter;
use App\Enums\Kodik\KodikCatalogSort;
use App\Enums\Kodik\KodikCommonFilter;
use InvalidArgumentException;

/**
 * Filters accepted by the /translations/v2 endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetTranslationsRequest
 */
final readonly class KodikTranslationsFilters extends KodikCommonFilters
{
    public static function make(): self
    {
        return new self(
            [],
            array_merge(KodikCommonFilter::values(), KodikAllOfFilter::values(), KodikCatalogFilter::values()),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withSort(KodikCatalogSort $sort): static
    {
        return $this->put(KodikCatalogFilter::SORT, $sort->value);
    }
}
