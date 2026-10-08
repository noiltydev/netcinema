<?php

declare(strict_types=1);

namespace App\Repositories\Pagination;

use App\Repositories\Contracts\PaginationStrategy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class OffsetStrategy implements PaginationStrategy
{
    public function apply(Builder $builder, string $idColumn, int $perPage): LengthAwarePaginator
    {
        return $builder->orderBy($idColumn)->paginate($perPage);
    }
}
