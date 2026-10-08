<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Funteam;
use Illuminate\Support\Collection;

/**
 * @extends Repository<Funteam>
 */
class FunteamRepository extends Repository
{
    /**
     * @param Collection<int, string> $slugs
     *
     * @return Collection<int, Funteam>
     */
    public function getManyBySlugs(Collection $slugs): Collection
    {
        return $this->modelClass::query()->whereIn('slug', $slugs)->get();
    }
}
