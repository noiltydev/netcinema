<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

use App\Enums\Contracts\KodikFilterKey;

/**
 * The `_and` counterparts of the list filters that every endpoint accepts.
 *
 * Each one is documented alongside its plain counterpart: the plain filter keeps materials holding
 * at least one of the given values, the `_and` one keeps only those holding all of them.
 */
enum KodikAllOfFilter: string implements KodikFilterKey
{
    case HAS_FIELD = 'has_field_and';
    case COUNTRIES = 'countries_and';
    case GENRES = 'genres_and';
    case ANIME_GENRES = 'anime_genres_and';
    case DRAMA_GENRES = 'drama_genres_and';
    case ALL_GENRES = 'all_genres_and';
    case ACTORS = 'actors_and';
    case DIRECTORS = 'directors_and';
    case PRODUCERS = 'producers_and';
    case WRITERS = 'writers_and';
    case COMPOSERS = 'composers_and';
    case EDITORS = 'editors_and';
    case DESIGNERS = 'designers_and';
    case OPERATORS = 'operators_and';
    case MYDRAMALIST_TAGS = 'mydramalist_tags_and';
    case ANIME_STUDIOS = 'anime_studios_and';
    case ANIME_LICENSED_BY = 'anime_licensed_by_and';

    public function queryKey(): string
    {
        return $this->value;
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
