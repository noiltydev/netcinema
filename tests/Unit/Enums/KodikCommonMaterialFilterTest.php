<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikCommonMaterialFilter;
use App\Enums\KodikCommonFilter;
use PHPUnit\Framework\TestCase;

class KodikCommonMaterialFilterTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('translation_id', KodikCommonMaterialFilter::TRANSLATION_ID->value);
        static::assertSame('block_translations', KodikCommonMaterialFilter::BLOCK_TRANSLATIONS->value);
    }

    public function test_values_returns_all_common_material_filter_keys(): void
    {
        static::assertSame(
            [
                'translation_id',
                'block_translations',
            ],
            KodikCommonMaterialFilter::values(),
        );
    }

    public function test_common_material_filters_do_not_overlap_shared_filters(): void
    {
        static::assertSame([], array_intersect(KodikCommonMaterialFilter::values(), KodikCommonFilter::values()));
    }
}
