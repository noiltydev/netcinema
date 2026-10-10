<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use InvalidArgumentException;

/**
 * Filters accepted by the /list endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetListRequest
 */
final readonly class KodikListFilters extends KodikFilters
{
    public static function make(): self
    {
        return new self([], array_merge(KodikFilter::values(), KodikListFilter::values()));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): static
    {
        return $this->put(KodikListFilter::LIMIT, $limit);
    }

    /**
     * Escape hatch for filters without a dedicated method.
     *
     * @throws InvalidArgumentException
     */
    public function with(KodikFilter|KodikListFilter $key, string|int|bool $value): static
    {
        return $this->put($key, $value);
    }
}
