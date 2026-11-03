<?php

declare(strict_types=1);

namespace App\Services\FunteamImport;

use App\Enums\SourceProviderName;
use App\Services\FunteamImport\Contracts\FunteamImportAdapter;
use App\Values\Funteam\FunteamImportData;
use App\Values\Kodik\KodikTranslation;
use App\Values\Source\SourceProvider;
use Illuminate\Support\Collection;

class KodikFunteamAdapter implements FunteamImportAdapter
{
    /**
     * @param array<int, KodikTranslation> $translations
     */
    public function __construct(
        private readonly array $translations,
    ) {}

    /**
     * @return Collection<int, FunteamImportData>
     */
    public function funteams(): Collection
    {
        /** @var array<int|string, string> $namesBySlug */
        $namesBySlug = [];
        /** @var array<int|string, array<int, SourceProvider>> $sourcesBySlug */
        $sourcesBySlug = [];

        foreach ($this->translations as $translation) {
            $slug = $translation->slug();

            if ($slug === '') {
                continue;
            }

            $namesBySlug[$slug] ??= $translation->name();
            $sourcesBySlug[$slug][$translation->id] = SourceProvider::make(
                SourceProviderName::KODIK,
                (string) $translation->id,
            );
        }

        /** @var array<int, FunteamImportData> $rows */
        $rows = [];

        foreach ($namesBySlug as $slug => $name) {
            $rows[] = FunteamImportData::make(
                name: $name,
                slug: (string) $slug,
                sources: array_values($sourcesBySlug[$slug]),
            );
        }

        return collect($rows);
    }
}
