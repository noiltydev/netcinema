<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Contracts;

use App\Enums\Contracts\KodikFilterKey;
use App\Enums\Kodik\KodikAllOfFilter;
use App\Enums\Kodik\KodikCatalogFilter;
use App\Enums\Kodik\KodikCommonFilter;
use App\Enums\Kodik\KodikCommonMaterialFilter;
use App\Enums\Kodik\KodikListFilter;
use App\Enums\Kodik\KodikSearchFilter;
use App\Enums\Kodik\KodikSharedListSearchFilter;
use PHPUnit\Framework\TestCase;

class KodikFilterKeyTest extends TestCase
{
    public function test_every_filter_enum_exposes_its_backed_value_as_the_query_key(): void
    {
        static::assertSame('types', KodikCommonFilter::TYPES->queryKey());
        static::assertSame('countries_and', KodikAllOfFilter::COUNTRIES->queryKey());
        static::assertSame('sort', KodikCatalogFilter::SORT->queryKey());
        static::assertSame('limit', KodikSharedListSearchFilter::LIMIT->queryKey());
        static::assertSame('order', KodikListFilter::ORDER->queryKey());
        static::assertSame('title', KodikSearchFilter::TITLE->queryKey());
    }

    public function test_every_filter_enum_is_accepted_through_the_contract(): void
    {
        static::assertInstanceOf(KodikFilterKey::class, KodikCommonFilter::TYPES);
        static::assertInstanceOf(KodikFilterKey::class, KodikAllOfFilter::COUNTRIES);
        static::assertInstanceOf(KodikFilterKey::class, KodikCatalogFilter::SORT);
        static::assertInstanceOf(KodikFilterKey::class, KodikCommonMaterialFilter::TRANSLATION_ID);
        static::assertInstanceOf(KodikFilterKey::class, KodikSharedListSearchFilter::LIMIT);
        static::assertInstanceOf(KodikFilterKey::class, KodikListFilter::ORDER);
        static::assertInstanceOf(KodikFilterKey::class, KodikSearchFilter::TITLE);
    }
}
