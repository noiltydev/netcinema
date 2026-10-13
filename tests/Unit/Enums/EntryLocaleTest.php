<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\EntryLocale;
use PHPUnit\Framework\TestCase;

class EntryLocaleTest extends TestCase
{
    public function test_enum_cases_match_specification(): void
    {
        static::assertSame('ru', EntryLocale::RU->value);
        static::assertSame('en', EntryLocale::EN->value);
        static::assertSame('ja', EntryLocale::JA->value);
    }

    public function test_values_returns_every_locale(): void
    {
        static::assertSame(
            [
                'ru',
                'en',
                'ja',
            ],
            EntryLocale::values(),
        );
    }

    public function test_enum_has_expected_case_count(): void
    {
        static::assertCount(3, EntryLocale::cases());
    }
}
