<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\SourceBuilder;
use App\Enums\SourceProvider;
use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['sourceable_type', 'sourceable_id', 'provider', 'external_id'])]
#[UseEloquentBuilder(SourceBuilder::class)]
class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory;

    public static function query(): SourceBuilder
    {
        /** @var SourceBuilder */
        return parent::query();
    }

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'provider' => SourceProvider::class,
        ];
    }
}
