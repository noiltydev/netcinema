<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikRatingMpaa;
use PHPUnit\Framework\TestCase;

class KodikRatingMpaaTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('G', KodikRatingMpaa::G->value);
        static::assertSame('PG', KodikRatingMpaa::PG->value);
        static::assertSame('PG-13', KodikRatingMpaa::PG_13->value);
        static::assertSame('R', KodikRatingMpaa::R->value);
    }

    public function test_r_plus_keeps_the_plus_sign_in_its_wire_value(): void
    {
        static::assertSame('R+', KodikRatingMpaa::R_PLUS->value);
    }

    public function test_rx_keeps_the_documented_mixed_case(): void
    {
        static::assertSame('Rx', KodikRatingMpaa::RX->value);
    }

    public function test_values_returns_every_rating(): void
    {
        static::assertSame(
            [
                'G',
                'PG',
                'PG-13',
                'R',
                'R+',
                'Rx',
            ],
            KodikRatingMpaa::values(),
        );
    }
}
