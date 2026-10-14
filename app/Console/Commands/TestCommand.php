<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\KodikCommonFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Jobs\TestJob;
use App\Services\KodikService;
use App\Values\Kodik\KodikListFilters;
use App\Values\Kodik\KodikMaterial;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

#[Signature('kodik:test')]
#[Description('Command description')]
class TestCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(KodikService $kodikService): int
    {
        $seen = [];
        $buffer = [];
        $jobs = [];

        $filters = KodikListFilters::make()
            ->with(KodikCommonFilter::SORT, 'year')
            ->with(KodikListFilter::ORDER, 'desc')
            ->withField(KodikMaterialField::SHIKIMORI_ID)
            ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
            ->withLimit(100);

        $response = $kodikService->getList($filters);

        while (true) {
            /** @var Collection<int, KodikMaterial> $results */
            $results = collect($response->results);

            /** @var KodikMaterial $material */
            foreach ($results as $material) {
                $id = (int) $material->shikimoriId;

                if (isset($seen[$id])) {
                    continue;
                }

                $seen[$id] = true;
                $buffer[] = $id;
            }

            while (count($buffer) >= 1000) {
                $jobs[] = new TestJob(array_splice($buffer, 0, 1000));
                $this->components->info('Dispatched a batch of 1000 IDs to the queue.');
            }

            if (!$response->hasNextPage()) {
                break;
            }

            $response = $kodikService->getList($filters, $response->nextCursor());
        }

        if (count($buffer)) {
            $jobs[] = new TestJob($buffer);
            $this->components->info(sprintf('Dispatched remaining %d IDs to the queue.', count($buffer)));
        }

        $batch = Bus::batch($jobs)->name('test_batch')->dispatch();

        $this->components->success(sprintf('All jobs have been dispatched to the queue! Batch: %s', $batch->id));

        return self::SUCCESS;
    }
}
