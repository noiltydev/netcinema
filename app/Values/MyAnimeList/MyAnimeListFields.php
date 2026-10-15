<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeDetailField;
use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Enums\MyAnimeList\MyAnimeListMangaDetailField;
use App\Enums\MyAnimeList\MyAnimeListMangaNodeField;
use App\Enums\MyAnimeList\MyAnimeListMediaKind;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

/**
 * The fields filter of one endpoint, built from the fields that endpoint documents so a manga-only
 * field can never reach an anime request and an undocumented sub-field cannot reach MyAnimeList at all.
 *
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class MyAnimeListFields implements Arrayable
{
    /**
     * Sub-fields MyAnimeList documents for each anime field, nested to the depth the API nests them.
     *
     * @var array<string, array<string, array<string, array>>>
     */
    private const array ANIME_SUB_FIELDS = [
        'my_list_status' => [
            'status' => [],
            'score' => [],
            'num_episodes_watched' => [],
            'is_rewatching' => [],
            'start_date' => [],
            'finish_date' => [],
            'priority' => [],
            'num_times_rewatched' => [],
            'rewatch_value' => [],
            'tags' => [],
            'comments' => [],
            'updated_at' => [],
        ],
        'start_season' => [
            'year' => [],
            'season' => [],
        ],
        'broadcast' => [
            'day_of_the_week' => [],
            'start_time' => [],
        ],
        'statistics' => [
            'num_list_users' => [],
            'status' => [
                'watching' => [],
                'completed' => [],
                'on_hold' => [],
                'dropped' => [],
                'plan_to_watch' => [],
            ],
        ],
    ];

    /**
     * @var array<string, array<string, array<string, array>>>
     */
    private const array MANGA_SUB_FIELDS = [
        'my_list_status' => [
            'status' => [],
            'score' => [],
            'num_volumes_read' => [],
            'num_chapters_read' => [],
            'is_rereading' => [],
            'start_date' => [],
            'finish_date' => [],
            'priority' => [],
            'num_times_reread' => [],
            'reread_value' => [],
            'tags' => [],
            'comments' => [],
            'updated_at' => [],
        ],
        'authors' => [
            'node' => [
                'id' => [],
                'first_name' => [],
                'last_name' => [],
            ],
            'role' => [],
        ],
        'serialization' => [
            'node' => [
                'id' => [],
                'name' => [],
            ],
            'role' => [],
        ],
    ];

    /**
     * @param array<string, MyAnimeListFieldSelection> $selections
     */
    private function __construct(
        private array $selections,
        private MyAnimeListMediaKind $mediaKind,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public static function fromAnimeNode(MyAnimeListAnimeNodeField ...$fields): self
    {
        return self::fromNames(MyAnimeListMediaKind::ANIME, array_map(
            static fn (MyAnimeListAnimeNodeField $field): string => $field->value,
            $fields,
        ));
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromAnimeDetail(MyAnimeListAnimeDetailField ...$fields): self
    {
        return self::fromNames(MyAnimeListMediaKind::ANIME, array_map(
            static fn (MyAnimeListAnimeDetailField $field): string => $field->value,
            $fields,
        ));
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromMangaNode(MyAnimeListMangaNodeField ...$fields): self
    {
        return self::fromNames(MyAnimeListMediaKind::MANGA, array_map(
            static fn (MyAnimeListMangaNodeField $field): string => $field->value,
            $fields,
        ));
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromMangaDetail(MyAnimeListMangaDetailField ...$fields): self
    {
        return self::fromNames(MyAnimeListMediaKind::MANGA, array_map(
            static fn (MyAnimeListMangaDetailField $field): string => $field->value,
            $fields,
        ));
    }

    /**
     * Narrows a field the request already asks for; adding the same selection twice keeps the last.
     *
     * @throws InvalidArgumentException
     */
    public function withSelection(MyAnimeListFieldSelection $selection): self
    {
        throw_unless(
            array_key_exists($selection->name, $this->selections),
            InvalidArgumentException::class,
            sprintf('Field is not requested by this endpoint: %s', $selection->name),
        );

        self::assertDocumentedSubFields($selection, $this->subFieldTree());

        $selections = $this->selections;
        $selections[$selection->name] = $selection;

        return new self(selections: $selections, mediaKind: $this->mediaKind);
    }

    public function toQueryValue(): string
    {
        $selections = array_map(
            static fn (MyAnimeListFieldSelection $selection): string => $selection->toQueryValue(),
            array_values($this->selections),
        );

        return implode(',', $selections);
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'media_kind' => $this->mediaKind,
            'selections' => array_map(
                static fn (MyAnimeListFieldSelection $selection): array => $selection->toArray(),
                array_values($this->selections),
            ),
        ];
    }

    /**
     * @param array<string> $names
     *
     * @throws InvalidArgumentException
     */
    private static function fromNames(MyAnimeListMediaKind $mediaKind, array $names): self
    {
        $selections = [];

        foreach ($names as $name) {
            $selections[$name] = MyAnimeListFieldSelection::make($name);
        }

        return new self(selections: $selections, mediaKind: $mediaKind);
    }

    /**
     * @return array<string, array<string, array<string, array>>>
     */
    private function subFieldTree(): array
    {
        return match ($this->mediaKind) {
            MyAnimeListMediaKind::ANIME => self::ANIME_SUB_FIELDS,
            MyAnimeListMediaKind::MANGA => self::MANGA_SUB_FIELDS,
        };
    }

    /**
     * @param array<string, array<string, array>> $documentedSubFields
     *
     * @throws InvalidArgumentException
     */
    private static function assertDocumentedSubFields(
        MyAnimeListFieldSelection $selection,
        array $documentedSubFields,
    ): void {
        $allowed = $documentedSubFields[$selection->name] ?? null;

        throw_if(
            $allowed === null,
            InvalidArgumentException::class,
            sprintf('Field has no documented sub-fields: %s', $selection->name),
        );

        foreach ($selection->children as $child) {
            throw_unless(
                array_key_exists($child->name, $allowed),
                InvalidArgumentException::class,
                sprintf('Undocumented sub-field of %s: %s', $selection->name, $child->name),
            );

            self::assertDocumentedSubFields($child, $allowed);
        }
    }
}
