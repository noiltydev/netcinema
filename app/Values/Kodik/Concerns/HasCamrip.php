<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\Kodik\KodikSharedListSearchFilter;
use InvalidArgumentException;

trait HasCamrip
{
    /**
     * @throws InvalidArgumentException
     */
    public function withCamrip(bool $camrip = true): static
    {
        return $this->put(KodikSharedListSearchFilter::CAMRIP, $camrip);
    }
}
