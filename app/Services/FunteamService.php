<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SourceProviderName as ProviderName;
use App\Models\Funteam;
use App\Models\Source;
use App\Repositories\FunteamRepository;
use App\Repositories\SourceRepository;
use App\Values\Kodik\KodikTranslation;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FunteamService
{
    private const int UPSERT_CHUNK_SIZE = 100;

    public function __construct(
        private readonly FunteamRepository $funteamRepository,
        private readonly SourceRepository $sourceRepository,
    )
    {
    }

    /**
     * @param array<int, KodikTranslation> $translations
     * @param (callable(int $processedCount): void)|null $onChunkImported
     *
     * @throws RuntimeException
     */
    public function importFromKodik(array $translations, ?callable $onChunkImported = null): int
    {
        $rows = $this->makeImportRows($translations);

        foreach ($rows->chunk(self::UPSERT_CHUNK_SIZE) as $chunk) {
            $this->importChunk($chunk);

            if ($onChunkImported !== null) {
                $onChunkImported($chunk->count());
            }
        }

        return $rows->count();
    }

    /**
     * @param array<int, KodikTranslation> $translations
     *
     * @return Collection<int, array{name: string, slug: string, kodik_ids: array<int, int>}>
     */
    private function makeImportRows(array $translations): Collection
    {
        return collect($translations)
            ->map(static fn(KodikTranslation $translation): array => [
                'name' => $translation->name(),
                'slug' => $translation->slug(),
                'kodik_id' => $translation->id,
            ])
            ->reject(static fn(array $row): bool => $row['slug'] === '')
            ->groupBy('slug')
            ->map(static function (Collection $slugGroup): array {
                $firstRow = $slugGroup->first();

                return [
                    'name' => $firstRow['name'],
                    'slug' => $firstRow['slug'],
                    'kodik_ids' => $slugGroup->pluck('kodik_id')->unique()->values()->all(),
                ];
            })
            ->values();
    }

    /**
     * @param Collection<int, array{name: string, slug: string, kodik_ids: array<int, int>}> $rows
     *
     * @throws RuntimeException
     */
    private function importChunk(Collection $rows): void
    {
        DB::transaction(function () use ($rows): void {
            $importedAt = now();
            $linkedKodikFunteamIds = $this->sourceRepository->getFunteamIdsByExternalIdsFromProvider(
                $rows->flatMap(static fn(array $row): array => $row['kodik_ids'])->map('strval')->unique(),
                ProviderName::KODIK,
            );

            $this->upsertFunteams($rows, $importedAt, $linkedKodikFunteamIds);
            $this->linkFunteamsToKodikSources($rows, $importedAt, $linkedKodikFunteamIds);
        });
    }

    /**
     * @param Collection<int, array{name: string, slug: string, kodik_ids: array<int, int>}> $rows
     * @param Collection<string, int> $linkedKodikFunteamIds
     */
    private function upsertFunteams(Collection $rows, CarbonInterface $importedAt, Collection $linkedKodikFunteamIds): void
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $rows
            ->reject(fn(array $row): bool => $this->hasLinkedKodikId($row, $linkedKodikFunteamIds))
            ->map(static fn(array $row): array => [
                'name' => $row['name'],
                'slug' => $row['slug'],
                'created_at' => $importedAt,
                'updated_at' => $importedAt,
            ])
            ->values()
            ->all();

        if ($items === []) {
            return;
        }

        Funteam::query()->upsert(
            $items,
            ['slug'],
            ['name', 'updated_at'],
        );
    }

    /**
     * @param Collection<int, array{name: string, slug: string, kodik_ids: array<int, int>}> $rows
     * @param Collection<string, int> $linkedKodikFunteamIds
     *
     * @throws RuntimeException
     */
    private function linkFunteamsToKodikSources(
        Collection $rows,
        CarbonInterface $importedAt,
        Collection $linkedKodikFunteamIds,
    ) : void
    {
        /** @var Collection<string, Funteam> $funteamsBySlug */
        $funteamsBySlug = $this->funteamRepository
            ->getManyBySlugs($rows->pluck('slug'))
            ->keyBy('slug');

        /** @var array<int, array<string, mixed>> $sourceRows */
        $sourceRows = $rows
            ->flatMap(fn(array $row): array => $this->makeKodikSourceRows(
                $funteamsBySlug->get($row['slug']),
                $row,
                $importedAt,
                $linkedKodikFunteamIds,
            ))
            ->values()
            ->all();

        if ($sourceRows === []) {
            return;
        }

        Source::query()->insert($sourceRows);
    }

    /**
     * @param array{name: string, slug: string, kodik_ids: array<int, int>} $row
     * @param Collection<string, int> $linkedKodikFunteamIds
     *
     * @return array<int, array<string, mixed>>
     */
    private function makeKodikSourceRows(
        ?Funteam $funteam,
        array $row,
        CarbonInterface $importedAt,
        Collection $linkedKodikFunteamIds,
    ) : array
    {
        if ($funteam === null) {
            return [];
        }

        return array_map(
            static fn(int $kodikId): array => [
                'sourceable_type' => $funteam->getMorphClass(),
                'sourceable_id' => $funteam->id,
                'provider_name' => ProviderName::KODIK->value,
                'external_id' => (string)$kodikId,
                'created_at' => $importedAt,
                'updated_at' => $importedAt,
            ],
            $this->unlinkedKodikIdsOf($funteam, $row, $linkedKodikFunteamIds),
        );
    }

    /**
     * @param array{name: string, slug: string, kodik_ids: array<int, int>} $row
     * @param Collection<string, int> $linkedKodikFunteamIds
     */
    private function hasLinkedKodikId(array $row, Collection $linkedKodikFunteamIds): bool
    {
        return collect($row['kodik_ids'])->contains(
            static fn(int $kodikId): bool => $linkedKodikFunteamIds->has((string)$kodikId),
        );
    }

    /**
     * @param array{name: string, slug: string, kodik_ids: array<int, int>} $row
     * @param Collection<string, int> $linkedKodikFunteamIds
     *
     * @return array<int, int>
     *
     * @throws RuntimeException
     */
    private function unlinkedKodikIdsOf(Funteam $funteam, array $row, Collection $linkedKodikFunteamIds): array
    {
        $unlinkedKodikIds = [];

        foreach ($row['kodik_ids'] as $kodikId) {
            $linkedFunteamId = $linkedKodikFunteamIds->get((string)$kodikId);

            throw_if(
                $linkedFunteamId !== null && $linkedFunteamId !== $funteam->id,
                RuntimeException::class,
                sprintf(
                    'Kodik translation %d is already linked to funteam #%d and cannot be linked to funteam #%d.',
                    $kodikId,
                    $linkedFunteamId,
                    $funteam->id,
                ),
            );

            if ($linkedFunteamId === null) {
                $unlinkedKodikIds[] = $kodikId;
            }
        }

        return $unlinkedKodikIds;
    }
}
