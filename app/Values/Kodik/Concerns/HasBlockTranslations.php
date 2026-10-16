<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\Kodik\KodikCommonMaterialFilter;
use InvalidArgumentException;

trait HasBlockTranslations
{
    /**
     * @throws InvalidArgumentException
     */
    public function withBlockTranslations(int ...$ids): static
    {
        return $this->put(KodikCommonMaterialFilter::BLOCK_TRANSLATIONS, implode(',', $ids));
    }
}
