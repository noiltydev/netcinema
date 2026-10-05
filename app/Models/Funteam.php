<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\FunteamBuilder;
use App\Models\Concerns\Funteams\HasFunteamRelationships;
use Database\Factories\FunteamFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
#[UseEloquentBuilder(FunteamBuilder::class)]
class Funteam extends Model
{
    /** @use HasFactory<FunteamFactory> */
    use HasFactory, HasFunteamRelationships;

    public static function query(): FunteamBuilder
    {
        /** @var FunteamBuilder */
        return parent::query();
    }
}
