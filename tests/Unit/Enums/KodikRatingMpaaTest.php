<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikRatingMpaa;
use PHPUnit\Framework\TestCase;

class KodikRatingMpaaTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        $this->assertSame('G', KodikRatingMpaa::G->value);
        $this->assertSame('PG', KodikRatingMpaa::PG->value);
        $this->assertSame('PG-13', KodikRatingMpaa::PG_13->value);
        $this->assertSame('R', KodikRatingMpaa::R->value);
    }

    public function test_r_plus_keeps_the_plus_sign_in_its_wire_value(): void
    {
        $this->assertSame('R+', KodikRatingMpaa::R_PLUS->value);
    }

    public function test_rx_keeps_the_documented_mixed_case(): void
    {
        $this->assertSame('Rx', KodikRatingMpaa::RX->value);
    }

    public function test_values_returns_every_rating(): void
    {
        $this->assertSame([
            'G',
            'PG',
            'PG-13',
            'R',
            'R+',
            'Rx',
        ], KodikRatingMpaa::values());
    }
}
