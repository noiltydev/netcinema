<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EntryLocale;
use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikTranslationType;
use App\Enums\SourceProviderName;
use App\Enums\TranslationBalancer;
use App\Enums\TranslationKind;
use App\Models\Funteam;
use App\Models\Source;
use App\Models\Translation;
use App\Repositories\SourceRepository;
use App\Services\KodikService;
use App\Values\Kodik\KodikFilters;
use App\Values\Kodik\KodikMaterial;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('kodik:run')]
#[Description('Command description')]
class TestCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(
        KodikService $kodikService,
        SourceRepository $sourceRepository,
    )
    {
        $res = $kodikService->getList(
            KodikFilters::forList()
                ->withLimit(100)
                ->with(KodikListFilter::ORDER, 'asc')
                ->withField(KodikMaterialField::SHIKIMORI_ID)
                ->with(KodikFilter::SORT, 'year')
                ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL),
        );

        /** @var array<int, KodikMaterial> $results */
        $results = $res->results;

        $funteams = Funteam::with('sources')->get();

        $funteamsBySource = $funteams
            ->flatMap(
                static fn(Funteam $funteam) => $funteam->sources->map(
                    static fn(Source $source) => [
                        'key' => "{$source->provider_name->value}:{$source->external_id}",
                        'funteam' => $funteam,
                    ]
                ),
            )
            ->keyBy('key');

        foreach ($results as $item) {

            $translation = $item->translation;

            $providerName = SourceProviderName::KODIK;
            $externalId = $translation->id;

            $key = "{$providerName->value}:{$externalId}";

            /** @var Funteam $funteam */
            $funteam = $funteamsBySource->get($key)['funteam'] ?? null;
if (is_null($funteam)) dd($key, $translation);
            Translation::firstOrCreate(
                [
                    'balancer_name' => TranslationBalancer::KODIK,
                    'external_id' => $item->id
                ],
                [
                    'funteam_id' => $funteam->id,
                    'balancer_name' => TranslationBalancer::KODIK,
                    'external_id' => $item->id,
                    'kind' => $translation->type === KodikTranslationType::VOICE
                        ? TranslationKind::DUB
                        : TranslationKind::SUB,
                    'locale' => EntryLocale::RU,
                ]
            );
        }

    }
}
