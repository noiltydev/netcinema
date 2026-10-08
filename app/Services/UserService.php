<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
    )
    {
    }
}
