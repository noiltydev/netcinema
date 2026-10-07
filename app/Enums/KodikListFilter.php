<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikListFilter: string
{
    case LIMIT = 'limit';
    case ORDER = 'order';
    case TRANSLATION_ID = 'translation_id';
    case BLOCK_TRANSLATIONS = 'block_translations';
    case CAMRIP = 'camrip';
    case WITH_SEASONS = 'with_seasons';
    case WITH_EPISODES = 'with_episodes';
    case WITH_EPISODES_DATA = 'with_episodes_data';
    case WITH_PAGE_LINKS = 'with_page_links';
    case NOT_BLOCKED_IN = 'not_blocked_in';
    case NOT_BLOCKED_FOR_ME = 'not_blocked_for_me';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
