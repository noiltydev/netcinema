<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Funteam;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Funteam>
 */
class FunteamBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
