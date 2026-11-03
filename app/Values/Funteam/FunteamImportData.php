<?php

declare(strict_types=1);

namespace App\Values\Funteam;

use App\Values\Source\SourceProvider;
use InvalidArgumentException;

final readonly class FunteamImportData
{
    /**
     * @param non-empty-list<SourceProvider> $sources
     */
    private function __construct(
        public string $name,
        public string $slug,
        public array $sources,
    ) {}

    /**
     * @param list<SourceProvider> $sources
     */
    public static function make(string $name, string $slug, array $sources): self
    {
        throw_if(
            $slug === '',
            InvalidArgumentException::class,
            sprintf('Funteam name "%s" does not produce a slug.', $name),
        );

        throw_if(
            $sources === [],
            InvalidArgumentException::class,
            sprintf('Funteam "%s" must have at least one source.', $name),
        );

        /** @var non-empty-list<SourceProvider> $sources */
        return new self(name: $name, slug: $slug, sources: $sources);
    }
}
