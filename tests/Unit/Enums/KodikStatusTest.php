<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikStatus;
use PHPUnit\Framework\TestCase;

class KodikStatusTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('anons', KodikStatus::ANONS->value);
        static::assertSame('ongoing', KodikStatus::ONGOING->value);
        static::assertSame('released', KodikStatus::RELEASED->value);
    }

    public function test_values_returns_every_status(): void
    {
        static::assertSame(
            [
                'anons',
                'ongoing',
                'released',
            ],
            KodikStatus::values(),
        );
    }

    public function test_the_same_vocabulary_serves_every_status_filter(): void
    {
        static::assertSame(['anons', 'ongoing', 'released'], KodikStatus::values());
    }
}
