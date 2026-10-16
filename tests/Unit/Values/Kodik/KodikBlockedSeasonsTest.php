<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Values\Kodik\KodikBlockedSeasons;
use PHPUnit\Framework\TestCase;

class KodikBlockedSeasonsTest extends TestCase
{
    public function test_from_array_reads_a_whole_serial_blocked_as_the_all_marker(): void
    {
        $blocked = KodikBlockedSeasons::fromArray('all');

        static::assertTrue($blocked->all);
        static::assertSame([], $blocked->series);
    }

    public function test_from_array_reads_separately_blocked_series(): void
    {
        $blocked = KodikBlockedSeasons::fromArray([
            '5' => 'all',
            '7' => ['1', '2', '3', '5'],
        ]);

        static::assertFalse($blocked->all);
        static::assertSame([7 => ['1', '2', '3', '5']], $blocked->series);
    }

    public function test_from_array_reads_an_empty_object_as_nothing_blocked(): void
    {
        $blocked = KodikBlockedSeasons::fromArray([]);

        static::assertFalse($blocked->all);
        static::assertSame([], $blocked->series);
    }
}
