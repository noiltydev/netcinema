<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

/**
 * Fields the /list endpoint sorts materials by, distinct from the catalogue sort below because
 * the two share a query key while accepting different values.
 *
 * @see KodikCatalogSort
 */
enum KodikListSort: string
{
    case YEAR = 'year';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
    case KINOPOISK_RATING = 'kinopoisk_rating';
    case IMDB_RATING = 'imdb_rating';
    case SHIKIMORI_RATING = 'shikimori_rating';
}
