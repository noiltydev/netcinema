<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikMaterialField;
use PHPUnit\Framework\TestCase;

class KodikMaterialFieldTest extends TestCase
{
    private const string UNDOCUMENTED_FIELD = 'tmdb_id';

    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('kinopoisk_id', KodikMaterialField::KINOPOISK_ID->value);
        static::assertSame('imdb_id', KodikMaterialField::IMDB_ID->value);
        static::assertSame('mdl_id', KodikMaterialField::MDL_ID->value);
        static::assertSame('worldart_link', KodikMaterialField::WORLDART_LINK->value);
        static::assertSame('shikimori_id', KodikMaterialField::SHIKIMORI_ID->value);
    }

    public function test_values_returns_every_field(): void
    {
        static::assertSame(
            [
                'kinopoisk_id',
                'imdb_id',
                'mdl_id',
                'worldart_link',
                'shikimori_id',
            ],
            KodikMaterialField::values(),
        );
    }

    public function test_try_from_returns_null_for_undocumented_field(): void
    {
        static::assertNull(KodikMaterialField::tryFrom(self::UNDOCUMENTED_FIELD));
    }
}
