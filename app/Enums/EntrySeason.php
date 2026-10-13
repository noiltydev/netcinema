<?php

declare(strict_types=1);

namespace App\Enums;

use Carbon\CarbonImmutable;

enum EntrySeason: string
{
    case WINTER = 'winter';
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case FALL = 'fall';

    public static function fromMonth(int $month): self
    {
        return match (true) {
            $month <= 3 => self::WINTER,
            $month <= 6 => self::SPRING,
            $month <= 9 => self::SUMMER,
            default => self::FALL,
        };
    }

    public static function fromDate(CarbonImmutable $date): self
    {
        return self::fromMonth($date->month);
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
