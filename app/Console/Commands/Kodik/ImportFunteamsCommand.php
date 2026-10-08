<?php

declare(strict_types=1);

namespace App\Console\Commands\Kodik;

use App\Enums\KodikMaterialType;
use App\Services\FunteamService;
use App\Services\KodikService;
use App\Values\Kodik\KodikFilters;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('kodik:funteams:import')]
#[Description('Populate the funteams table from Kodik translations')]
class ImportFunteamsCommand extends Command
{
    public function handle(
        KodikService $kodikService,
        FunteamService $funteamService,
    ) : int
    {
        $res = $kodikService->getTranslations(
            KodikFilters::forTranslations()->withTypes(
                KodikMaterialType::ANIME,
                KodikMaterialType::ANIME_SERIAL,
            ),
        );

        $translations = $res->results;

        $progressBar = $this->output->createProgressBar(count($translations));
        $progressBar->setMessage('Importing funteams');
        $progressBar->start();

        $importedCount = $funteamService->importFromKodik(
            $translations,
            static fn(int $processedCount) => $progressBar->advance($processedCount),
        );

        $progressBar->finish();

        $this->components->info(sprintf('Imported %d funteams', $importedCount));

        return self::SUCCESS;
    }
}
