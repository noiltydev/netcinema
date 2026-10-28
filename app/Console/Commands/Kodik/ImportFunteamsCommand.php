<?php

declare(strict_types=1);

namespace App\Console\Commands\Kodik;

use App\Enums\Kodik\KodikMaterialType;
use App\Services\FunteamImport\FunteamImportService;
use App\Services\FunteamImport\KodikFunteamAdapter;
use App\Services\Integrations\KodikService;
use App\Values\Kodik\KodikTranslationsFilters;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('kodik:funteams:import')]
#[Description('Populate the funteams table from Kodik translations')]
class ImportFunteamsCommand extends Command
{
    public function handle(KodikService $kodikService, FunteamImportService $importService): int
    {
        $filters = KodikTranslationsFilters::make()->withTypes(
            KodikMaterialType::ANIME,
            KodikMaterialType::ANIME_SERIAL,
        );

        $translations = $kodikService->getTranslations($filters)->results;
        $funteams = (new KodikFunteamAdapter($translations))->funteams();

        $progressBar = $this->output->createProgressBar($funteams->count());
        $progressBar->setMessage('Importing funteams');
        $progressBar->start();

        $importedCount = $importService->import($funteams, $progressBar->advance(...));

        $progressBar->finish();

        $this->components->info(sprintf('Imported %d funteams', $importedCount));

        return self::SUCCESS;
    }
}
