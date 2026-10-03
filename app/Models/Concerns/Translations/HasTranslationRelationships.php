<?php

declare(strict_types=1);

namespace App\Models\Concerns\Translations;

use App\Models\Funteam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasTranslationRelationships
{
    public function funteam(): BelongsTo
    {
        return $this->belongsTo(Funteam::class);
    }
}
