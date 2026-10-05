<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\TranslationBuilder;
use App\Enums\TranslationBalancer;
use App\Enums\TranslationKind;
use App\Models\Concerns\Translations\HasTranslationRelationships;
use Database\Factories\TranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['funteam_id', 'balancer', 'external_id', 'kind', 'locale'])]
#[UseEloquentBuilder(TranslationBuilder::class)]
class Translation extends Model
{
    /** @use HasFactory<TranslationFactory> */
    use HasFactory, HasTranslationRelationships;

    protected $with = ['funteam'];

    public static function query(): TranslationBuilder
    {
        /** @var TranslationBuilder */
        return parent::query();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'balancer' => TranslationBalancer::class,
            'kind' => TranslationKind::class,
        ];
    }
}
