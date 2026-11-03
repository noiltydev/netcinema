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

    /**
     * @param list<self> $rows
     */
    public static function merge(array $rows): self
    {
        throw_if($rows === [], InvalidArgumentException::class, 'Cannot merge an empty list of funteam import rows.');

        /** @var non-empty-list<self> $rows */
        $first = $rows[0];

        throw_if(
            collect($rows)->contains(static fn (self $row): bool => $row->slug !== $first->slug),
            InvalidArgumentException::class,
            'Cannot merge funteam import rows with different slugs.',
        );

        $sources = collect($rows)
            ->flatMap(static fn (self $row): array => $row->sources)
            ->unique(static fn (SourceProvider $source): string => $source->key())
            ->values()
            ->all();

        return self::make(name: $first->name, slug: $first->slug, sources: $sources);
    }
}
