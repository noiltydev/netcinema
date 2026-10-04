<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Translation;
use App\Repositories\TranslationRepository;

class TranslationService
{
    public function __construct(
        private readonly TranslationRepository $repository,
    )
    {
    }
}
