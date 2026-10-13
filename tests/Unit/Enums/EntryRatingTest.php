<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\EntryRating;
use PHPUnit\Framework\TestCase;

class EntryRatingTest extends TestCase
{
    public function test_enum_cases_match_specification(): void
    {
        static::assertSame('g', EntryRating::G->value);
        static::assertSame('pg', EntryRating::PG->value);
        static::assertSame('pg_13', EntryRating::PG_13->value);
        static::assertSame('r', EntryRating::R->value);
        static::assertSame('r_plus', EntryRating::R_PLUS->value);
        static::assertSame('rx', EntryRating::RX->value);
    }

    public function test_values_returns_every_rating(): void
    {
        static::assertSame(
            [
                'g',
                'pg',
                'pg_13',
                'r',
                'r_plus',
                'rx',
            ],
            EntryRating::values(),
        );
    }

    public function test_enum_has_expected_case_count(): void
    {
        static::assertCount(6, EntryRating::cases());
    }
}
