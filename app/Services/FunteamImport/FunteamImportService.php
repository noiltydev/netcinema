<?php

declare(strict_types=1);

namespace App\Services\FunteamImport;

use App\Models\Funteam;
use App\Repositories\FunteamRepository;
use App\Services\SourceLinkService;
use App\Values\Funteam\FunteamImportData;
use App\Values\Source\SourceProvider;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FunteamImportService
{
    private const int UPSERT_CHUNK_SIZE = 100;

    public function __construct(
        private readonly FunteamRepository $funteamRepository,
        private readonly SourceLinkService $sourceLinkService,
    ) {}

    /**
     * @param iterable<FunteamImportData> $funteams
     * @param (callable(int $processedCount): void)|null $onChunkImported
     *
     * @throws RuntimeException
     */
    public function import(iterable $funteams, ?callable $onChunkImported = null): int
    {
        $rows = $this->mergeRowsBySlug($funteams);

        foreach ($rows->chunk(self::UPSERT_CHUNK_SIZE) as $chunk) {
            $this->importChunk($chunk);

            if ($onChunkImported !== null) {
                $onChunkImported($chunk->count());
            }
        }

        return $rows->count();
    }

    /**
     * @param iterable<FunteamImportData> $funteams
     *
     * @return Collection<int, FunteamImportData>
     */
    private function mergeRowsBySlug(iterable $funteams): Collection
    {
        return collect($funteams)
            ->groupBy(static fn (FunteamImportData $data): string => $data->slug)
            ->map(static function (Collection $group): FunteamImportData {
                /** @var non-empty-list<FunteamImportData> $rows */
                $rows = $group->values()->all();

                return FunteamImportData::merge($rows);
            })
            ->values();
    }

    /**
     * @param Collection<int, FunteamImportData> $rows
     *
     * @throws RuntimeException
     */
    private function importChunk(Collection $rows): void
    {
        DB::transaction(function () use ($rows): void {
            $importedAt = now();
            $linkedSourceableIds = $this->sourceLinkService->getLinkedSourceableIds(
                Funteam::class,
                $rows->flatMap(static fn (FunteamImportData $row): array => $row->sources),
            );

            $this->upsertFunteams($rows, $importedAt, $linkedSourceableIds);

            /** @var Collection<string, Funteam> $funteamsBySlug */
            $funteamsBySlug = $this->funteamRepository->getManyBySlugs($rows->pluck('slug'))->keyBy('slug');

            foreach ($rows as $row) {
                $funteam = $funteamsBySlug->get($row->slug);

                if ($funteam === null) {
                    continue;
                }

                $this->sourceLinkService->attachSources($funteam, $row->sources, $linkedSourceableIds, $importedAt);
            }
        });
    }

    /**
     * @param Collection<int, FunteamImportData> $rows
     * @param Collection<string, int<0, max>> $linkedSourceableIds
     */
    private function upsertFunteams(
        Collection $rows,
        CarbonInterface $importedAt,
        Collection $linkedSourceableIds,
    ): void {
        /** @var array<int, array<string, mixed>> $items */
        $items = $rows
            ->reject(static fn (FunteamImportData $row): bool => self::hasLinkedSource($row, $linkedSourceableIds))
            ->map(static fn (FunteamImportData $row): array => [
                'name' => $row->name,
                'slug' => $row->slug,
                'created_at' => $importedAt,
                'updated_at' => $importedAt,
            ])
            ->values()
            ->all();

        if ($items === []) {
            return;
        }

        Funteam::query()->upsert($items, ['slug'], ['name', 'updated_at']);
    }

    /**
     * @param Collection<string, int<0, max>> $linkedSourceableIds
     */
    private static function hasLinkedSource(FunteamImportData $row, Collection $linkedSourceableIds): bool
    {
        return collect($row->sources)->contains(static fn (SourceProvider $source): bool => $linkedSourceableIds->has(
            $source->key(),
        ));
    }
}
