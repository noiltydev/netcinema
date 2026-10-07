<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Services\KodikService;
use App\Values\Kodik\KodikFilters;
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
    )
    {
        dd($kodikService->getList(
            KodikFilters::forList()
                ->withLimit(100)
                ->with(KodikListFilter::ORDER, 'asc')
                ->withField(KodikMaterialField::SHIKIMORI_ID)
                ->with(KodikFilter::SORT, 'year')
                ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL),
        ));
    }
}
