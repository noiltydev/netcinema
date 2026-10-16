<?php

declare(strict_types=1);

namespace App\Enums\MyAnimeList;

/**
 * Fields a node returned by /manga and /manga/ranking accepts in the fields filter.
 *
 * @see \App\Values\MyAnimeList\MyAnimeListFields
 */
enum MyAnimeListMangaNodeField: string
{
    case ID = 'id';
    case TITLE = 'title';
    case MAIN_PICTURE = 'main_picture';
    case ALTERNATIVE_TITLES = 'alternative_titles';
    case START_DATE = 'start_date';
    case END_DATE = 'end_date';
    case SYNOPSIS = 'synopsis';
    case MEAN = 'mean';
    case RANK = 'rank';
    case POPULARITY = 'popularity';
    case NUM_LIST_USERS = 'num_list_users';
    case NUM_SCORING_USERS = 'num_scoring_users';
    case NSFW = 'nsfw';
    case GENRES = 'genres';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
    case MEDIA_TYPE = 'media_type';
    case STATUS = 'status';
    case MY_LIST_STATUS = 'my_list_status';
    case NUM_VOLUMES = 'num_volumes';
    case NUM_CHAPTERS = 'num_chapters';
    case AUTHORS = 'authors';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
