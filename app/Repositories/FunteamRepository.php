<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
    public function getManyBySlugsWithSourcesFromProvider(
        Collection $slugs,
        SourceProviderName $providerName,
    ) : Collection
    {
        return $this->modelClass::query()
            ->whereIn('slug', $slugs)
            ->with([
                'sources' => static fn(MorphMany $sources): MorphMany => $sources
                    ->where('provider_name', $providerName),
            ])
            ->get();
    }
}
