<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Funteam;
use App\Repositories\FunteamRepository;

class FunteamService
{
    public function __construct(
        private readonly FunteamRepository $repository,
    )
    {
    }
}
