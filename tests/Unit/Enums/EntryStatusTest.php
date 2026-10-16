<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\EntryStatus;
use PHPUnit\Framework\TestCase;

class EntryStatusTest extends TestCase
{
    public function test_enum_cases_match_specification(): void
    {
        static::assertSame('anons', EntryStatus::ANONS->value);
        static::assertSame('ongoing', EntryStatus::ONGOING->value);
        static::assertSame('released', EntryStatus::RELEASED->value);
    }

    public function test_values_returns_every_status(): void
    {
        static::assertSame(
            [
                'anons',
                'ongoing',
                'released',
            ],
            EntryStatus::values(),
        );
    }

    public function test_enum_has_expected_case_count(): void
    {
        static::assertCount(3, EntryStatus::cases());
    }
}
