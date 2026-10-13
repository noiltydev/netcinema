<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\KodikCommonMaterialFilter;
use InvalidArgumentException;

trait HasTranslationId
{
    /**
     * @throws InvalidArgumentException
     */
    public function withTranslationId(int ...$ids): static
    {
        return $this->put(KodikCommonMaterialFilter::TRANSLATION_ID, implode(',', $ids));
    }
}
