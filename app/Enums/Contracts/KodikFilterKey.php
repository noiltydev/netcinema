<?php

declare(strict_types=1);

namespace App\Enums\Contracts;

interface KodikFilterKey
{
    public function queryKey(): string;
}
