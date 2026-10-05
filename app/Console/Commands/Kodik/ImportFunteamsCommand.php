<?php

declare(strict_types=1);

namespace App\Console\Commands\Kodik;

use App\Helpers\Kodik;
use App\Models\Funteam;
use App\Services\FunteamService;
use App\Services\KodikService;
use App\Values\Kodik\KodikTranslation;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

#[Signature('kodik:funteams:import')]
#[Description('Populate the funteams table from Kodik translations')]
class ImportFunteamsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(
        KodikService $kodikService,
        FunteamService $funteamService,
    ) : int
    {
        $translationsList = $kodikService->getTranslations([
            'types' => 'anime,anime-serial'
        ]);

        /** @var Collection<int, KodikTranslation> $result */
        $result = collect($translationsList->results);

        $timestamp = now()->toDateTimeString();

        $rows = $result->map(static fn(KodikTranslation $translation): array => [
            'name' => $name = Kodik::prepareTranslationTitle($translation->title),
            'slug' => Str::slug($name),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ])
        ->reject(static fn(array $row): bool => $row['slug'] === '')
        ->unique('slug')
        ->values();

        $progressBar = $this->output->createProgressBar($rows->count());
        $progressBar->setMessage('Importing funteams');
        $progressBar->start();

        foreach (collect($rows)->chunk(100) as $chunk) {

            Funteam::query()->upsert(
                $chunk->all(),
                ['slug'],
                ['name', 'updated_at'],
            );

            $progressBar?->advance($chunk->count());
        }

        $progressBar->finish();

        $this->info(' success');

        return self::SUCCESS;
    }
}
