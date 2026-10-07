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
     * @return Collection<int, array{kodik_id: int, name: string, slug: string}>
     */
    private function makeImportRows(array $translations): Collection
    {
        return collect($translations)
            ->map(static fn(KodikTranslation $translation): array => [
                'kodik_id' => $translation->id,
                'name' => $translation->name(),
                'slug' => $translation->slug(),
            ])
            ->reject(static fn(array $row): bool => $row['slug'] === '')
            ->unique('slug')
            ->values();
    }

    /**
     * @param Collection<int, array{kodik_id: int, name: string, slug: string}> $rows
     *
     * @throws RuntimeException
     */
    private function importChunk(Collection $rows): void
    {
        DB::transaction(function () use ($rows): void {
            $importedAt = now();
            $linkedKodikIds = $this->sourceRepository->getFunteamExternalIdsFromProvider(
                $rows->pluck('kodik_id')->map('strval'),
                ProviderName::KODIK,
            );

            $this->upsertFunteams($rows, $importedAt, $linkedKodikIds);
            $this->linkFunteamsToKodikSources($rows, $importedAt);
        });
    }

    /**
     * @param Collection<int, array{kodik_id: int, name: string, slug: string}> $rows
     * @param Collection<int, int> $linkedKodikIds
     */
    private function upsertFunteams(Collection $rows, CarbonInterface $importedAt, Collection $linkedKodikIds): void
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $rows
            ->reject(static fn(array $row): bool => $linkedKodikIds->contains($row['kodik_id']))
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
     * @param Collection<int, array{kodik_id: int, name: string, slug: string}> $rows
     *
     * @throws RuntimeException
     */
    private function linkFunteamsToKodikSources(Collection $rows, CarbonInterface $importedAt): void
    {
        /** @var Collection<string, Funteam> $funteamsBySlug */
        $funteamsBySlug = $this->funteamRepository
            ->getManyBySlugsWithSourcesFromProvider(
                $rows->pluck('slug'),
                ProviderName::KODIK,
            )
            ->keyBy('slug');

        /** @var array<int, array<string, mixed>> $sourceRows */
        $sourceRows = $rows
            ->map(fn(array $row): ?array => $this->makeKodikSourceRow(
                $funteamsBySlug->get($row['slug']),
                $row,
                $importedAt,
            ))
            ->filter()
            ->values()
            ->all();

        if ($sourceRows === []) {
            return;
        }

        Source::query()->insert($sourceRows);
    }

    /**
     * @param array{kodik_id: int, name: string, slug: string} $row
     *
     * @return array<string, mixed>|null
     *
     * @throws RuntimeException
     */
    private function makeKodikSourceRow(?Funteam $funteam, array $row, CarbonInterface $importedAt): ?array
    {
        if ($funteam === null) {
            return null;
        }

        $kodikExternalId = $this->kodikExternalIdOf($funteam);

        throw_if(
            $kodikExternalId !== null && $kodikExternalId !== (int)$row['kodik_id'],
            RuntimeException::class,
            sprintf(
                'Funteam "%s" is already linked to Kodik translation %d and cannot be linked to translation %d.',
                $funteam->slug,
                $kodikExternalId,
                (int)$row['kodik_id'],
            ),
        );

        if ($kodikExternalId !== null) {
            return null;
        }

        return [
            'sourceable_type' => $funteam->getMorphClass(),
            'sourceable_id' => $funteam->id,
            'provider_name' => ProviderName::KODIK->value,
            'external_id' => (string)$row['kodik_id'],
            'created_at' => $importedAt,
            'updated_at' => $importedAt,
        ];
    }

    private function kodikExternalIdOf(Funteam $funteam): ?int
    {
        $kodikSource = $funteam->sources->first(
            static fn(Source $source): bool => $source->provider_name === ProviderName::KODIK
        );

        if ($kodikSource === null) {
            return null;
        }

        return (int)$kodikSource->external_id;
    }
}
