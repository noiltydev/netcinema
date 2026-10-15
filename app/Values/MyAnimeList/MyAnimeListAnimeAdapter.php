<?php

declare(strict_types=1);

namespace App\Values\MyAnimeList;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;

/**
 * Translates MyAnimeList's vocabulary into the project's, and is the only place that
 * translation lives.
 *
 * Every method takes the raw string the API sent and returns a domain enum, or null when
 * the value has no counterpart. MyAnimeList declares these fields as unconstrained strings
 * rather than enums, and its responses already carry values missing from its own
 * documentation, so nothing here may assume a closed set.
 */
final class MyAnimeListAnimeAdapter
{
    private function __construct() {}

    public static function kind(?string $mediaType): ?AnimeKind
    {
        return $mediaType === null ? null : AnimeKind::tryFrom($mediaType);
    }

    public static function status(?string $status): ?EntryStatus
    {
        return match ($status) {
            'finished_airing' => EntryStatus::RELEASED,
            'currently_airing' => EntryStatus::ONGOING,
            'not_yet_aired' => EntryStatus::ANONS,
            default => null,
        };
    }

    public static function rating(?string $rating): ?EntryRating
    {
        if ($rating === null) {
            return null;
        }

        return $rating === 'r+' ? EntryRating::R_PLUS : EntryRating::tryFrom($rating);
    }

    public static function season(?string $season): ?EntrySeason
    {
        return $season === null ? null : EntrySeason::tryFrom($season);
    }
}
