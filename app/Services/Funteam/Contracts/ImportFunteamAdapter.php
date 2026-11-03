<?php

declare(strict_types=1);

namespace App\Services\Funteam\Contracts;

use App\Values\Funteam\FunteamImportData;
use Illuminate\Support\Collection;

interface ImportFunteamAdapter
{
    /**
     * @return Collection<int, FunteamImportData>
     */
    public function funteams(): Collection;
}
