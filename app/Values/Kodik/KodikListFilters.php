<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Kodik\KodikCommonFilter;
use App\Enums\Kodik\KodikCommonMaterialFilter;
use App\Enums\Kodik\KodikListFilter;
use App\Enums\Kodik\KodikSharedListSearchFilter;
use App\Values\Kodik\Concerns\HasBlockTranslations;
use App\Values\Kodik\Concerns\HasCamrip;
use App\Values\Kodik\Concerns\HasLimit;
use App\Values\Kodik\Concerns\HasNotBlocked;
use App\Values\Kodik\Concerns\HasSeasonsEpisodes;
use App\Values\Kodik\Concerns\HasTranslationId;

/**
 * Filters accepted by the /list endpoint.
 *
 * @see \App\Http\Integrations\Kodik\Requests\GetListRequest
 */
final readonly class KodikListFilters extends KodikCommonFilters
{
    use HasLimit;
    use HasTranslationId;
    use HasBlockTranslations;
    use HasCamrip;
    use HasSeasonsEpisodes;
    use HasNotBlocked;

    public static function make(): self
    {
        return new self(
            [],
            array_merge(
                KodikCommonFilter::values(),
                KodikCommonMaterialFilter::values(),
                KodikSharedListSearchFilter::values(),
                KodikListFilter::values(),
            ),
        );
    }
}
