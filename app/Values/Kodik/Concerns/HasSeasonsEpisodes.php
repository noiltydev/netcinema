<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\KodikSharedListSearchFilter;
use InvalidArgumentException;

trait HasSeasonsEpisodes
{
    /**
     * @throws InvalidArgumentException
     */
    public function withSeasons(bool $withSeasons = true): static
    {
        return $this->put(KodikSharedListSearchFilter::WITH_SEASONS, $withSeasons);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withEpisodes(bool $withEpisodes = true): static
    {
        return $this->put(KodikSharedListSearchFilter::WITH_EPISODES, $withEpisodes);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withEpisodesData(bool $withEpisodesData = true): static
    {
        return $this->put(KodikSharedListSearchFilter::WITH_EPISODES_DATA, $withEpisodesData);
    }
}
