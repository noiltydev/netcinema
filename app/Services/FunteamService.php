<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SourceProviderName as ProviderName;
use App\Models\Funteam;
use App\Models\Source;
use App\Repositories\FunteamRepository;
use App\Values\Kodik\KodikTranslation;
use App\Values\Source\SourceProvider as Provider;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use RuntimeException;

class FunteamService
{
    private const int UPSERT_CHUNK_SIZE = 100;

    public function __construct(
        private readonly FunteamRepository $repository,
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
        $rows = collect($translations)
            ->map(static fn(KodikTranslation $translation): array => [
                'kodik_id' => $translation->id,
                'name' => $translation->name(),
                'slug' => $translation->slug(),
            ])
            ->reject(static fn(array $row): bool => $row['slug'] === '')
            ->unique('slug')
            ->values();

        foreach ($rows->chunk(self::UPSERT_CHUNK_SIZE) as $chunk) {

            /** @var Collection<int, Funteam> $funteams */
            $funteams = Funteam::query()->whereHasSourcesByNames(
                $chunk->map(static fn(array $row) => Provider::make(
                    ProviderName::KODIK,
                    strval($row['kodik_id']),
                )),
                [ProviderName::KODIK]
            )->with('sources')->get();

            /** @var Collection<int, Source> $sources */
            $sources = $funteams->flatMap(
                static fn(Funteam $funteam) => $funteam->sources
            );

            /** @var array<int, int> $externalIds */
            $externalIds = $sources->pluck('external_id')->map('intval')->all();

            $importedAt = now();

            /** @var Collection<int, array> $items */
            $items = $chunk
                ->reject(static fn(array $row) => in_array($row['kodik_id'], $externalIds))
                ->map(static fn(array $row): array => [
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'created_at' => $importedAt,
                    'updated_at' => $importedAt,
                ]);

            Funteam::query()->upsert(
                $items->toArray(),
                ['slug'],
                ['name', 'updated_at'],
            );

            $this->linkFunteamsToKodikSources($chunk, $importedAt);

            if ($onChunkImported !== null) {
                $onChunkImported($chunk->count());
            }
        }

        return $rows->count();
    }

    /**
     * @param Collection<int, array{kodik_id: int, name: string, slug: string}> $rows
     *
     * @throws RuntimeException
     */
    private function linkFunteamsToKodikSources(Collection $rows, CarbonInterface $importedAt): void
    {
        /** @var Collection<string, Funteam> $funteamsBySlug */
        $funteamsBySlug = Funteam::query()
            ->whereIn('slug', $rows->pluck('slug'))
            ->with('sources')
            ->get()
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
