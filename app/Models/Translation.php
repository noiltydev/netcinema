<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\TranslationBuilder;
use App\Enums\TranslationBalancer;
use App\Enums\TranslationKind;
use Database\Factories\TranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['funteam_id', 'balancer', 'external_id', 'kind', 'locale'])]
class Translation extends Model
{
    /** @use HasFactory<TranslationFactory> */
    use HasFactory;

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): TranslationBuilder
    {
        /** @var TranslationBuilder */
        return parent::query();
    }

    public function funteam(): BelongsTo
    {
        return $this->belongsTo(Funteam::class);
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
