<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

use App\Enums\Contracts\KodikFilterKey;

enum KodikSearchFilter: string implements KodikFilterKey
{
    case TITLE = 'title';
    case TITLE_ORIG = 'title_orig';
    case STRICT = 'strict';
    case FULL_MATCH = 'full_match';
    case ID = 'id';
    case PLAYER_LINK = 'player_link';
    case KINOPOISK_ID = 'kinopoisk_id';
    case IMDB_ID = 'imdb_id';
    case MDL_ID = 'mdl_id';
    case WORLDART_ANIMATION_ID = 'worldart_animation_id';
    case WORLDART_CINEMA_ID = 'worldart_cinema_id';
    case WORLDART_LINK = 'worldart_link';
    case SHIKIMORI_ID = 'shikimori_id';
    case SEASON = 'season';
    case EPISODE = 'episode';
    case HAS_FIELD_AND = 'has_field_and';
    case PRIORITIZE_TRANSLATIONS = 'prioritize_translations';
    case UNPRIORITIZE_TRANSLATIONS = 'unprioritize_translations';
    case PRIORITIZE_TRANSLATION_TYPE = 'prioritize_translation_type';

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
