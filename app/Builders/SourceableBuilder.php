<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\SourceProviderName as ProviderName;
use App\Values\Source\SourceProvider as Provider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @template TModel of Model
 * @extends Builder<TModel>
 */
abstract class SourceableBuilder extends Builder
{
    abstract protected function sourcesRelationName(): string;

    public function whereHasSources(iterable $sources): self
    {
        $sources = $this->toCollection($sources);

        if ($sources->isEmpty()) {
            // нет источников — гарантированно пустой результат
            return $this->whereRaw('1 = 0');
        }

        return $this->whereHas(
            $this->sourcesRelationName(),
            fn(Builder $query) => $this->applySourcesMatch($query, $sources),
        );
    }

    /**
     * @param iterable<Provider> $sources
     * @param array<ProviderName> $allowedNames
     */
    public function whereHasSourcesByNames(iterable $sources, array $allowedNames): self
    {
        $sources = $this->toCollection($sources)->filter(
            static fn(Provider $source) => in_array($source->providerName, $allowedNames, true)
        );

        return $this->whereHasSources($sources);
    }

    public function whereHasSourceNamed(ProviderName $source): self
    {
        return $this->whereHas(
            $this->sourcesRelationName(),
            static fn(Builder $query) => $query->where('name', $source->value),
        );
    }

    private function applySourcesMatch(Builder $query, Collection $sources): void
    {
        $query->where(
            function (Builder $query) use ($sources): void {
                foreach ($sources as $source) {
                    $query->orWhere(fn(Builder $query) => $this->matchSource($query, $source));
                }
            },
        );
    }

    private function matchSource(Builder $query, Provider $source): void
    {
        $query->where('name', $source->providerName)->where('external_id', $source->externalId);
    }

    private function toCollection(iterable $sources): Collection
    {
        return $sources instanceof Collection ? $sources : collect($sources);
    }
}
