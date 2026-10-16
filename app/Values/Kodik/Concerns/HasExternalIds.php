<?php

declare(strict_types=1);

namespace App\Values\Kodik\Concerns;

use App\Enums\Kodik\KodikSearchFilter;
use InvalidArgumentException;

/**
 * The external id filters of /search, each taking a single value: the API answers a comma
 * separated list with "Неправильный формат", so the several-ids-at-once behaviour the endpoint
 * documents needs a serialization this integration has not confirmed yet.
 */
trait HasExternalIds
{
    /**
     * @throws InvalidArgumentException
     */
    public function withKinopoiskId(string $kinopoiskId): static
    {
        return $this->put(KodikSearchFilter::KINOPOISK_ID, $kinopoiskId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withImdbId(string $imdbId): static
    {
        return $this->put(KodikSearchFilter::IMDB_ID, $imdbId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withMdlId(string $mdlId): static
    {
        return $this->put(KodikSearchFilter::MDL_ID, $mdlId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withWorldartAnimationId(string $worldartAnimationId): static
    {
        return $this->put(KodikSearchFilter::WORLDART_ANIMATION_ID, $worldartAnimationId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withWorldartCinemaId(string $worldartCinemaId): static
    {
        return $this->put(KodikSearchFilter::WORLDART_CINEMA_ID, $worldartCinemaId);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withWorldartLink(string $worldartLink): static
    {
        return $this->put(KodikSearchFilter::WORLDART_LINK, $worldartLink);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withShikimoriId(string $shikimoriId): static
    {
        return $this->put(KodikSearchFilter::SHIKIMORI_ID, $shikimoriId);
    }
}
