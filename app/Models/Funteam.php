<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\FunteamBuilder;
use App\Models\Concerns\Funteams\HasFunteamRelationships;
use App\Models\Concerns\MorphsToSources;
use App\Models\Contracts\Sourceable;
use Database\Factories\FunteamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
#[UseEloquentBuilder(FunteamBuilder::class)]
class Funteam extends Model implements Sourceable
{
    /** @use HasFactory<FunteamFactory> */
    use HasFactory;
    use HasFunteamRelationships;
    use MorphsToSources;
}
