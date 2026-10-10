<?php

declare(strict_types=1);

namespace App\Enums\Contracts;

/**
 * A Kodik query parameter name, backed by the endpoint scopes that accept it.
 */
interface KodikFilterKey
{
    public function queryKey(): string;
}
