<?php

declare(strict_types=1);

namespace App\Values\Concerns;

use BackedEnum;

/**
 * The query an endpoint scope accumulates, rebuilt on every write so a filter stays immutable.
 *
 * Key validation stays in the scope that owns put(), because each provider accepts a different
 * set of keys and narrowing that set is what makes the type worth declaring per provider.
 *
 * @phpstan-consistent-constructor
 */
trait CommonQueryFilters
{
    /**
     * @var array<string, string|int>
     */
    protected readonly array $query;

    /**
     * @var array<string>
     */
    private readonly array $allowedKeys;

    /**
     * @param array<string, string|int> $query
     * @param array<string> $allowedKeys
     */
    protected function __construct(array $query, array $allowedKeys)
    {
        $this->query = $query;
        $this->allowedKeys = $allowedKeys;
    }

    /**
     * @return array<string, string|int>
     */
    public function toQuery(): array
    {
        return $this->query;
    }

    /**
     * @param array<array-key, BackedEnum> $values
     */
    private static function commaSeparated(array $values): string
    {
        return implode(',', array_column($values, 'value'));
    }

    private static function normalizeValue(string|int|bool $value): string|int
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
