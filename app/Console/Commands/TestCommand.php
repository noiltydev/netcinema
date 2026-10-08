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
use App\Services\KodikService;
use App\Values\Kodik\KodikFilters;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RuntimeException;

#[Signature('kodik:run')]
#[Description('Command description')]
class TestCommand extends Command
{
    public function handle(KodikService $kodikService): int
    {
        $res = $kodikService->getList(
            KodikFilters::forList()
                ->withLimit(100)
                ->with(KodikListFilter::ORDER, 'asc')
                ->withField(KodikMaterialField::SHIKIMORI_ID)
                ->with(KodikFilter::SORT, 'year')
                ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL),
        );

        $funteamsBySource = Funteam::query()
            ->with('sources')
            ->get()
            ->flatMap(static fn (Funteam $funteam) => $funteam->sources->mapWithKeys(static fn (Source $source): array => [
                sprintf('%s:%s', $source->provider_name->value, $source->external_id) => $funteam,
            ]));

        foreach ($res->results as $material) {
            $material->translation ?? throw new RuntimeException(sprintf(
                'Kodik material %s carries no translation.',
                $material->id,
            ));

            $key = sprintf('%s:%s', SourceProviderName::KODIK->value, $material->translation->id);
            $funteam = $funteamsBySource->get($key);

            throw_unless(
                $funteam instanceof Funteam,
                RuntimeException::class,
                sprintf('No funteam is linked to Kodik translation %s.', $material->translation->id),
            );

            Translation::query()->firstOrCreate([
                'balancer_name' => TranslationBalancer::KODIK,
                'external_id' => $material->id,
            ], [
                'funteam_id' => $funteam->id,
                'kind' => $material->translation->type === KodikTranslationType::VOICE
                    ? TranslationKind::DUB
                    : TranslationKind::SUB,
                'locale' => EntryLocale::RU,
            ]);
        }

        return self::SUCCESS;
    }
}
