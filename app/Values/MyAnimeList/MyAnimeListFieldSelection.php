<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

/**
 * One entry of the fields filter, optionally narrowed to the sub-fields MyAnimeList documents
 * for it, so my_list_status{status,score} and statistics{status{watching}} survive the round trip.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListFieldSelection implements Arrayable
{
    /**
     * @param array<string, MyAnimeListFieldSelection> $children
     */
    private function __construct(
        public string $name,
        public array $children,
    ) {}

    /**
     * Plain names become leaf selections; nested selections keep their own subtree.
     *
     * @throws InvalidArgumentException
     */
    public static function make(string $name, string|self ...$children): self
    {
        throw_if($name === '', InvalidArgumentException::class, 'Field name must not be empty.');

        $selections = [];

        foreach ($children as $child) {
            $selection = $child instanceof self ? $child : self::make($child);

            $selections[$selection->name] = $selection;
        }

        return new self(name: $name, children: $selections);
    }

    public function toQueryValue(): string
    {
        if (!$this->children) {
            return $this->name;
        }

        $children = array_map(
            static fn (self $selection): string => $selection->toQueryValue(),
            array_values($this->children),
        );

        return sprintf('%s{%s}', $this->name, implode(',', $children));
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'children' => array_map(
                static fn (self $selection): array => $selection->toArray(),
                array_values($this->children),
            ),
        ];
    }
}
