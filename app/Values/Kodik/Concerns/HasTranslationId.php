<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\KodikListFilter;
use InvalidArgumentException;

trait HasTranslationId
{
    /**
     * @throws InvalidArgumentException
     */
    public function withTranslationId(int ...$ids): static
    {
        return $this->put(KodikListFilter::TRANSLATION_ID, implode(',', $ids));
    }
}
