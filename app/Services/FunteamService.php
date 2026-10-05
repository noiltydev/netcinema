<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Funteam;
use App\Repositories\FunteamRepository;
use App\Values\Kodik\KodikTranslation;

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
     * @return int
     */
    public function importFromKodikTranslations(array $translations, ?callable $onChunkImported = null): int
    {
        $rows = collect($translations)
            ->map(static fn(KodikTranslation $translation): array => [
                'name' => $translation->name(),
                'slug' => $translation->slug(),
            ])
            ->reject(static fn(array $row): bool => $row['slug'] === '')
            ->unique('slug')
            ->values();

        foreach ($rows->chunk(self::UPSERT_CHUNK_SIZE) as $chunk) {

            Funteam::query()->upsert(
                $chunk->all(),
                ['slug'],
                ['name'],
            );

            if ($onChunkImported !== null) {
                $onChunkImported($chunk->count());
            }
        }

        return $rows->count();
    }
}
