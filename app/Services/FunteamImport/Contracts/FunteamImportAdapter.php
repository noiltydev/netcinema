<?php

declare(strict_types=1);

namespace App\Services\FunteamImport\Contracts;

use App\Values\Funteam\FunteamImportData;
use Illuminate\Support\Collection;

interface FunteamImportAdapter
{
    /**
     * @return Collection<int, FunteamImportData>
     */
    public function funteams(): Collection;
}
