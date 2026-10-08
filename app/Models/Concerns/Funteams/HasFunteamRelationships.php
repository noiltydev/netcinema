<?php

declare(strict_types=1);

namespace App\Models\Concerns\Funteams;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasFunteamRelationships
{
    /** @return HasMany<Translation, $this> */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }
}
