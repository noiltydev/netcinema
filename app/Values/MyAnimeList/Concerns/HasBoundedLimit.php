<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList\Concerns;

use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use InvalidArgumentException;

/**
 * Every MyAnimeList list endpoint rejects a limit outside 1..maxLimit() with a 400, so the bound
 * belongs to the endpoint scope rather than to a shared range the caller has to remember.
 */
trait HasBoundedLimit
{
    abstract public static function maxLimit(): int;

    /**
     * @throws InvalidArgumentException
     */
    public function withLimit(int $limit): static
    {
        $maxLimit = static::maxLimit();

        throw_if(
            $limit < 1 || $limit > $maxLimit,
            InvalidArgumentException::class,
            sprintf('Limit must be between 1 and %d, got %d.', $maxLimit, $limit),
        );

        return $this->put(MyAnimeListCommonFilter::LIMIT, $limit);
    }
}
