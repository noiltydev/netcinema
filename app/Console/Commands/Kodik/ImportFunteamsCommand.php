<?php

declare(strict_types=1);

namespace App\Console\Commands\Kodik;

use App\Enums\Kodik\KodikMaterialType;
use App\Services\FunteamImportService;
use App\Services\KodikService;
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

        $response = $kodikService->getTranslations($filters);

        $translations = $response->results;

        $progressBar = $this->output->createProgressBar(count($translations));
        $progressBar->setMessage('Importing funteams');
        $progressBar->start();

        $importedCount = $importService->importFromKodik($translations, $progressBar->advance(...));

        $progressBar->finish();

        $this->components->info(sprintf('Imported %d funteams', $importedCount));

        return self::SUCCESS;
    }
}
