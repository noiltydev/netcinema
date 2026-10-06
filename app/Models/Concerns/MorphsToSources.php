<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Source;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MorphsToSources
{
    public function sources(): MorphMany
    {
        return $this->morphMany(Source::class, 'sourceable');
    }
}
