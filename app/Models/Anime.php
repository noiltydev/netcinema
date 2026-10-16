<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\AnimeBuilder;
use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\AnimeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kind',
    'rating',
    'status',
    'title',
    'slug',
    'aired_at',
    'aired_year',
    'aired_season',
    'released_at',
    'episodes_count',
    'episodes_aired',
    'duration',
    'next_episode_at',
])]
#[UseEloquentBuilder(AnimeBuilder::class)]
class Anime extends Model
{
    /** @use HasFactory<AnimeFactory> */
    use HasFactory;
    use MorphsToSources;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => AnimeKind::class,
            'rating' => EntryRating::class,
            'status' => EntryStatus::class,
            'aired_season' => EntrySeason::class,
            'aired_year' => 'integer',
            'episodes_count' => 'integer',
            'episodes_aired' => 'integer',
            'duration' => 'integer',
        ];
    }
}
