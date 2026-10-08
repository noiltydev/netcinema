<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Funteam;

/**
 * @extends SourceableBuilder<Funteam>
 */
class FunteamBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
