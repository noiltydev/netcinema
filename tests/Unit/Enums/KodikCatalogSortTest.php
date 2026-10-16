<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikCatalogSort;
use PHPUnit\Framework\TestCase;

class KodikCatalogSortTest extends TestCase
{
    public function test_cases_match_documented_vocabulary(): void
    {
        static::assertSame('title', KodikCatalogSort::TITLE->value);
        static::assertSame('count', KodikCatalogSort::COUNT->value);
    }

    public function test_material_only_values_have_no_case(): void
    {
        static::assertNull(KodikCatalogSort::tryFrom('updated_at'));
        static::assertNull(KodikCatalogSort::tryFrom('imdb_rating'));
    }
}
