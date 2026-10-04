<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikFilter: string
{
    case Types = 'types';
    case Year = 'year';
    case TranslationType = 'translation_type';
    case HasField = 'has_field';
    case Lgbt = 'lgbt';
    case Sort = 'sort';
    case WithMaterialData = 'with_material_data';
    case Countries = 'countries';
    case Genres = 'genres';
    case AnimeGenres = 'anime_genres';
    case DramaGenres = 'drama_genres';
    case AllGenres = 'all_genres';
    case Duration = 'duration';
    case KinopoiskRating = 'kinopoisk_rating';
    case ImdbRating = 'imdb_rating';
    case ShikimoriRating = 'shikimori_rating';
    case MydramalistRating = 'mydramalist_rating';
    case Actors = 'actors';
    case Directors = 'directors';
    case Producers = 'producers';
    case Writers = 'writers';
    case Composers = 'composers';
    case Editors = 'editors';
    case Designers = 'designers';
    case Operators = 'operators';
    case RatingMpaa = 'rating_mpaa';
    case MinimalAge = 'minimal_age';
    case AnimeKind = 'anime_kind';
    case MydramalistTags = 'mydramalist_tags';
    case AnimeStatus = 'anime_status';
    case DramaStatus = 'drama_status';
    case AllStatus = 'all_status';
    case AnimeStudios = 'anime_studios';
    case AnimeLicensedBy = 'anime_licensed_by';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
