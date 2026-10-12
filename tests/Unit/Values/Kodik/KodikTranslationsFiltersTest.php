<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikCommonFilter;
use App\Enums\KodikMaterialType;
use App\Enums\KodikStatus;
use App\Values\Kodik\KodikTranslationsFilters;
use PHPUnit\Framework\TestCase;

class KodikTranslationsFiltersTest extends TestCase
{
    public function test_empty_scope_produces_an_empty_query(): void
    {
        static::assertSame([], KodikTranslationsFilters::make()->toQuery());
    }

    public function test_escape_hatch_accepts_any_shared_filter(): void
    {
        $query = KodikTranslationsFilters::make()->with(KodikCommonFilter::YEAR, '2023')->toQuery();

        static::assertSame(['year' => '2023'], $query);
    }

    public function test_shared_named_filters_are_available(): void
    {
        $query = KodikTranslationsFilters::make()
            ->withTypes(KodikMaterialType::ANIME)
            ->withAnimeStatus(KodikStatus::RELEASED)
            ->toQuery();

        static::assertSame(
            [
                'types' => 'anime',
                'anime_status' => 'released',
            ],
            $query,
        );
    }
}
