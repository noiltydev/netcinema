<?php

declare(strict_types=1);

namespace App\Console\Commands\Kodik;

use App\Enums\KodikFilter;
use App\Services\FunteamService;
use App\Services\KodikService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('kodik:funteams:import')]
#[Description('Populate the funteams table from Kodik translations')]
class ImportFunteamsCommand extends Command
{
    private const string IMPORTED_MATERIAL_TYPES = 'anime,anime-serial';

    public function handle(
        KodikService $kodikService,
        FunteamService $funteamService,
    ) : int
    {
        $res = $kodikService->getTranslations([
            KodikFilter::TYPES->value => self::IMPORTED_MATERIAL_TYPES,
        ]);

        $translations = $res->results;

        $progressBar = $this->output->createProgressBar(count($translations));
        $progressBar->setMessage('Importing funteams');
        $progressBar->start();

        $importedCount = $funteamService->importFromKodikTranslations(
            $translations,
            static function (int $processedCount) use ($progressBar): void {
                $progressBar->advance($processedCount);
            },
        );

        $progressBar->finish();

        $this->components->info(sprintf('Imported %d funteams', $importedCount));

        return self::SUCCESS;
    }
}
