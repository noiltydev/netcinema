<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\KodikService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('test:run')]
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
        $translationsList = $kodikService->getTranslations();

        dd(
            $translationsList->toArray()
        );
    }
}
