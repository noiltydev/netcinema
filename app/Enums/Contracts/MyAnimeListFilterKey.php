<?php

declare(strict_types=1);

namespace App\Enums\Contracts;

interface MyAnimeListFilterKey
{
    public function queryKey(): string;
}
