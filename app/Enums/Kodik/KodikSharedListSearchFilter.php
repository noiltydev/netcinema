<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

use App\Enums\Contracts\KodikFilterKey;

/**
 * Filters shared by /list and /search endpoints only (2 endpoints).
 */
enum KodikSharedListSearchFilter: string implements KodikFilterKey
{
    case CAMRIP = 'camrip';
    case WITH_SEASONS = 'with_seasons';
    case WITH_EPISODES = 'with_episodes';
    case WITH_EPISODES_DATA = 'with_episodes_data';
    case WITH_PAGE_LINKS = 'with_page_links';
    case NOT_BLOCKED_IN = 'not_blocked_in';
    case NOT_BLOCKED_FOR_ME = 'not_blocked_for_me';

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
