<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\Kodik\KodikListFilter;
use InvalidArgumentException;

trait HasLimit
{
    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): static
    {
        return $this->put(KodikListFilter::LIMIT, $limit);
    }
}
