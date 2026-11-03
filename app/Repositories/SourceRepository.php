<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Contracts\Sourceable;
use App\Models\Source;
use App\Values\Source\SourceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @extends Repository<Source>
 */
class SourceRepository extends Repository
{
    /**
     * @param class-string<Sourceable|Model> $sourceableClass
     * @param Collection<int, SourceProvider> $sources
     *
     * @return Collection<string, int<0, max>>
     */
    public function getSourceableIdsBySources(string $sourceableClass, Collection $sources): Collection
    {
        if ($sources->isEmpty()) {
            return collect();
        }

        return $this->modelClass::query()
            ->where('sourceable_type', Relation::getMorphAlias($sourceableClass))
            ->where(static function (Builder $query) use ($sources): void {
                foreach ($sources as $source) {
                    $query->orWhere(static function (Builder $query) use ($source): void {
                        $query->where('provider_name', $source->providerName)->where(
                            'external_id',
                            $source->externalId,
                        );
                    });
                }
            })
            ->get(['provider_name', 'external_id', 'sourceable_id'])
            ->mapWithKeys(static fn (Source $source): array => [
                SourceProvider::make($source->provider_name, $source->external_id)->key() =>
                    (int) $source->sourceable_id,
            ]);
    }
}
