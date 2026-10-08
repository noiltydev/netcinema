<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use App\Models\Source;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @extends Repository<Source>
 */
class SourceRepository extends Repository
{
    /**
     * @param Collection<int, string> $externalIds
     *
     * @return Collection<string, int>
     */
    public function getFunteamIdsByExternalIdsFromProvider(
        Collection $externalIds,
        SourceProviderName $providerName,
    ) : Collection
    {
        return $this->modelClass::query()
            ->where('sourceable_type', Relation::getMorphAlias(Funteam::class))
            ->where('provider_name', $providerName)
            ->whereIn('external_id', $externalIds)
            ->pluck('sourceable_id', 'external_id')
            ->map(static fn(mixed $funteamId): int => (int)$funteamId);
    }
}
