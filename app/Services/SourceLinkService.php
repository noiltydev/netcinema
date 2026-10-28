<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contracts\Sourceable;
use App\Models\Source;
use App\Repositories\SourceRepository;
use App\Values\Source\SourceProvider;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RuntimeException;

class SourceLinkService
{
    public function __construct(
        private readonly SourceRepository $sourceRepository,
    ) {}

    /**
     * @param class-string<Model> $sourceableClass
     * @param iterable<SourceProvider> $sources
     *
     * @return Collection<string, int<0, max>>
     */
    public function getLinkedSourceableIds(string $sourceableClass, iterable $sources): Collection
    {
        /** @var Collection<int, SourceProvider> $sources */
        $sources = collect($sources);

        if ($sources->isEmpty()) {
            return collect();
        }

        return $this->sourceRepository->getSourceableIdsBySources($sourceableClass, $sources);
    }

    /**
     * @param iterable<SourceProvider> $sources
     * @param Collection<string, int<0, max>> $linkedSourceableIds
     *
     * @throws RuntimeException
     */
    public function attachSources(
        Sourceable|Model $sourceable,
        iterable $sources,
        Collection $linkedSourceableIds,
        CarbonInterface $attachedAt,
    ): void {
        $sourceableId = (int) $sourceable->getKey();
        $sourceableType = $sourceable->getMorphClass();

        /** @var array<int, array<string, mixed>> $rows */
        $rows = [];

        foreach ($sources as $source) {
            $linkedSourceableId = $linkedSourceableIds->get($source->key());

            throw_if(
                $linkedSourceableId !== null && $linkedSourceableId !== $sourceableId,
                RuntimeException::class,
                sprintf(
                    'Source %s is already linked to %s #%d and cannot be linked to %s #%d.',
                    $source->key(),
                    $sourceableType,
                    $linkedSourceableId,
                    $sourceableType,
                    $sourceableId,
                ),
            );

            if ($linkedSourceableId !== null) {
                continue;
            }

            $rows[] = [
                'sourceable_type' => $sourceableType,
                'sourceable_id' => $sourceableId,
                'provider_name' => $source->providerName->value,
                'external_id' => $source->externalId,
                'created_at' => $attachedAt,
                'updated_at' => $attachedAt,
            ];

            $linkedSourceableIds->put($source->key(), $sourceableId);
        }

        if ($rows === []) {
            return;
        }

        Source::query()->insert($rows);
    }
}
