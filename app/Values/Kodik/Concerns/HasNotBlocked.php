<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\Kodik\KodikSharedListSearchFilter;
use InvalidArgumentException;

trait HasNotBlocked
{
    /**
     * @throws InvalidArgumentException
     */
    public function withNotBlockedIn(string ...$countries): static
    {
        return $this->put(KodikSharedListSearchFilter::NOT_BLOCKED_IN, implode(',', $countries));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withNotBlockedForMe(bool $notBlockedForMe = true): static
    {
        return $this->put(KodikSharedListSearchFilter::NOT_BLOCKED_FOR_ME, $notBlockedForMe);
    }
}
