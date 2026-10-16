<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikOrder;
use PHPUnit\Framework\TestCase;

class KodikOrderTest extends TestCase
{
    public function test_cases_match_documented_vocabulary(): void
    {
        static::assertSame('asc', KodikOrder::ASC->value);
        static::assertSame('desc', KodikOrder::DESC->value);
    }
}
