<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikFilter: string
{
    case TYPES = 'types';
    case YEAR = 'year';
    case TRANSLATION_TYPE = 'translation_type';
    case HAS_FIELD = 'has_field';
    case LGBT = 'lgbt';
    case SORT = 'sort';
    case WITH_MATERIAL_DATA = 'with_material_data';
    case COUNTRIES = 'countries';
    case GENRES = 'genres';
    case ANIME_GENRES = 'anime_genres';
    case DRAMA_GENRES = 'drama_genres';
    case ALL_GENRES = 'all_genres';
    case DURATION = 'duration';
    case KINOPOISK_RATING = 'kinopoisk_rating';
    case IMDB_RATING = 'imdb_rating';
    case SHIKIMORI_RATING = 'shikimori_rating';
    case MYDRAMALIST_RATING = 'mydramalist_rating';
    case ACTORS = 'actors';
    case DIRECTORS = 'directors';
    case PRODUCERS = 'producers';
    case WRITERS = 'writers';
    case COMPOSERS = 'composers';
    case EDITORS = 'editors';
    case DESIGNERS = 'designers';
    case OPERATORS = 'operators';
    case RATING_MPAA = 'rating_mpaa';
    case MINIMAL_AGE = 'minimal_age';
    case ANIME_KIND = 'anime_kind';
    case MYDRAMALIST_TAGS = 'mydramalist_tags';
    case ANIME_STATUS = 'anime_status';
    case DRAMA_STATUS = 'drama_status';
    case ALL_STATUS = 'all_status';
    case ANIME_STUDIOS = 'anime_studios';
    case ANIME_LICENSED_BY = 'anime_licensed_by';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
