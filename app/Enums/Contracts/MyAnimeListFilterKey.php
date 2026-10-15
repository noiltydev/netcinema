<?php

declare(strict_types=1);

namespace App\Enums\Contracts;

/**
 * A MyAnimeList query parameter name, backed by the endpoint scopes that accept it.
 */
interface MyAnimeListFilterKey
{
    public function queryKey(): string;
}
