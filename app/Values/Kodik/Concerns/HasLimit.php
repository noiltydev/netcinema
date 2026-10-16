<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\Kodik\KodikSharedListSearchFilter;
use InvalidArgumentException;

/**
 * Both the /list and the /search endpoint reject a limit outside 1..100, so the bound belongs to
 * the endpoint scope rather than to a range the caller has to remember.
 */
trait HasLimit
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

        return $this->put(KodikSharedListSearchFilter::LIMIT, $limit);
    }
}
