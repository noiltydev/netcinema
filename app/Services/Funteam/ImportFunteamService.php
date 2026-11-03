<?php

declare(strict_types=1);

namespace App\Services\Funteam;

use App\Models\Funteam;
use App\Repositories\FunteamRepository;
use App\Services\SourceService;
use App\Values\Funteam\FunteamImportData;
use App\Values\Source\SourceProvider;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportFunteamService
{
    private const int UPSERT_CHUNK_SIZE = 100;

    public function __construct(
        private readonly FunteamRepository $funteamRepository,
        private readonly SourceService $sourceService,
    ) {}

    /**
     * @param iterable<FunteamImportData> $funteams
     * @param (callable(int $processedCount): void)|null $onChunkImported
     *
     * @throws RuntimeException
     */
    public function importFunteams(iterable $funteams, ?callable $onChunkImported = null): int
    {
        $rows = collect($funteams);

        foreach ($rows->chunk(self::UPSERT_CHUNK_SIZE) as $chunk) {
            $this->importChunk($chunk);

            if ($onChunkImported !== null) {
                $onChunkImported($chunk->count());
            }
        }

        return $rows->count();
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

            $linkedSourceableIds = $this->sourceService->getLinkedSourceableIds(
                Funteam::class,
                $rows->flatMap(static fn (FunteamImportData $row): array => $row->sources),
            );

            $unlinkedRows = $rows->reject(
                static fn (FunteamImportData $row): bool => self::hasLinkedSource($row, $linkedSourceableIds),
            );

            $this->upsertRows($unlinkedRows, $importedAt);
            $this->attachSources($rows, $linkedSourceableIds, $importedAt);
        });
    }

    /**
     * @param Collection<int, FunteamImportData> $rows
     */
    private function upsertRows(Collection $rows, CarbonInterface $importedAt): void
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $rows
            ->unique('slug')
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
     * @param Collection<int, FunteamImportData> $rows
     * @param Collection<string, int<0, max>> $linkedSourceableIds
     */
    private function attachSources(Collection $rows, Collection $linkedSourceableIds, CarbonInterface $importedAt): void
    {
        /** @var Collection<int, Funteam> $funteamsById */
        $funteamsById = $this->funteamRepository
            ->getMany($linkedSourceableIds->values()->unique()->values()->all())
            ->keyBy(static fn (Funteam $funteam): int => (int) $funteam->getKey());

        /** @var Collection<string, Funteam> $funteamsBySlug */
        $funteamsBySlug = $this->funteamRepository
            ->getManyBySlugs($rows->pluck('slug')->unique()->values())
            ->keyBy('slug');

        foreach ($rows as $row) {
            $funteam = self::resolveFunteam($row, $linkedSourceableIds, $funteamsById, $funteamsBySlug);

            if ($funteam === null) {
                continue;
            }

            $funteamsById->put((int) $funteam->getKey(), $funteam);

            $this->sourceService->attachSources($funteam, $row->sources, $linkedSourceableIds, $importedAt);
        }
    }

    /**
     * @param Collection<string, int<0, max>> $linkedSourceableIds
     * @param Collection<int, Funteam> $funteamsById
     * @param Collection<string, Funteam> $funteamsBySlug
     *
     * @throws RuntimeException
     */
    private static function resolveFunteam(
        FunteamImportData $row,
        Collection $linkedSourceableIds,
        Collection $funteamsById,
        Collection $funteamsBySlug,
    ): ?Funteam {
        $linkedFunteamIds = collect($row->sources)
            ->map(static fn (SourceProvider $source): ?int => $linkedSourceableIds->get($source->key()))
            ->filter(static fn (?int $id): bool => $id !== null)
            ->unique()
            ->values();

        throw_if(
            $linkedFunteamIds->count() > 1,
            RuntimeException::class,
            sprintf('The sources of funteam "%s" are linked to different funteams.', $row->slug),
        );

        $linkedFunteamId = $linkedFunteamIds->first();

        if ($linkedFunteamId !== null) {
            return $funteamsById->get($linkedFunteamId);
        }

        return $funteamsBySlug->get($row->slug);
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
