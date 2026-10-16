<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\EntrySeason;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class EntrySeasonTest extends TestCase
{
    public function test_enum_cases_match_specification(): void
    {
        static::assertSame('winter', EntrySeason::WINTER->value);
        static::assertSame('spring', EntrySeason::SPRING->value);
        static::assertSame('summer', EntrySeason::SUMMER->value);
        static::assertSame('fall', EntrySeason::FALL->value);
    }

    public function test_values_returns_every_season(): void
    {
        static::assertSame(
            [
                'winter',
                'spring',
                'summer',
                'fall',
            ],
            EntrySeason::values(),
        );
    }

    public function test_enum_has_expected_case_count(): void
    {
        static::assertCount(4, EntrySeason::cases());
    }

    public function test_from_month_returns_correct_season(): void
    {
        static::assertSame(EntrySeason::WINTER, EntrySeason::fromMonth(1));
        static::assertSame(EntrySeason::WINTER, EntrySeason::fromMonth(2));
        static::assertSame(EntrySeason::WINTER, EntrySeason::fromMonth(3));

        static::assertSame(EntrySeason::SPRING, EntrySeason::fromMonth(4));
        static::assertSame(EntrySeason::SPRING, EntrySeason::fromMonth(5));
        static::assertSame(EntrySeason::SPRING, EntrySeason::fromMonth(6));

        static::assertSame(EntrySeason::SUMMER, EntrySeason::fromMonth(7));
        static::assertSame(EntrySeason::SUMMER, EntrySeason::fromMonth(8));
        static::assertSame(EntrySeason::SUMMER, EntrySeason::fromMonth(9));

        static::assertSame(EntrySeason::FALL, EntrySeason::fromMonth(10));
        static::assertSame(EntrySeason::FALL, EntrySeason::fromMonth(11));
        static::assertSame(EntrySeason::FALL, EntrySeason::fromMonth(12));
    }

    public function test_from_date_returns_correct_season(): void
    {
        static::assertSame(EntrySeason::WINTER, EntrySeason::fromDate(CarbonImmutable::create(2024, 2, 15)));
        static::assertSame(EntrySeason::SPRING, EntrySeason::fromDate(CarbonImmutable::create(2024, 5, 15)));
        static::assertSame(EntrySeason::SUMMER, EntrySeason::fromDate(CarbonImmutable::create(2024, 8, 15)));
        static::assertSame(EntrySeason::FALL, EntrySeason::fromDate(CarbonImmutable::create(2024, 11, 15)));
    }
}
