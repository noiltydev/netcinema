<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Anime;

/**
 * @extends SourceableBuilder<Anime>
 */
class AnimeBuilder extends SourceableBuilder
{
    protected function sourcesRelationName(): string
    {
        return 'sources';
    }
}
