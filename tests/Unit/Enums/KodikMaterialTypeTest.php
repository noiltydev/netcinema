<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\Kodik\KodikMaterialType;
use PHPUnit\Framework\TestCase;

class KodikMaterialTypeTest extends TestCase
{
    private const string UNDOCUMENTED_TYPE = 'documentary-film';

    public function test_enum_cases_match_documented_vocabulary(): void
    {
        static::assertSame('foreign-movie', KodikMaterialType::FOREIGN_MOVIE->value);
        static::assertSame('soviet-cartoon', KodikMaterialType::SOVIET_CARTOON->value);
        static::assertSame('foreign-cartoon', KodikMaterialType::FOREIGN_CARTOON->value);
        static::assertSame('russian-cartoon', KodikMaterialType::RUSSIAN_CARTOON->value);
        static::assertSame('anime', KodikMaterialType::ANIME->value);
        static::assertSame('russian-movie', KodikMaterialType::RUSSIAN_MOVIE->value);
        static::assertSame('cartoon-serial', KodikMaterialType::CARTOON_SERIAL->value);
        static::assertSame('documentary-serial', KodikMaterialType::DOCUMENTARY_SERIAL->value);
        static::assertSame('russian-serial', KodikMaterialType::RUSSIAN_SERIAL->value);
        static::assertSame('foreign-serial', KodikMaterialType::FOREIGN_SERIAL->value);
        static::assertSame('anime-serial', KodikMaterialType::ANIME_SERIAL->value);
        static::assertSame('multi-part-film', KodikMaterialType::MULTI_PART_FILM->value);
    }

    public function test_values_returns_every_material_type(): void
    {
        static::assertSame(
            [
                'foreign-movie',
                'soviet-cartoon',
                'foreign-cartoon',
                'russian-cartoon',
                'anime',
                'russian-movie',
                'cartoon-serial',
                'documentary-serial',
                'russian-serial',
                'foreign-serial',
                'anime-serial',
                'multi-part-film',
            ],
            KodikMaterialType::values(),
        );
    }

    public function test_comma_separated_joins_types_for_the_filter_value(): void
    {
        $joined = KodikMaterialType::commaSeparated([
            KodikMaterialType::ANIME,
            KodikMaterialType::ANIME_SERIAL,
        ]);

        static::assertSame('anime,anime-serial', $joined);
    }

    public function test_comma_separated_returns_empty_string_without_types(): void
    {
        static::assertSame('', KodikMaterialType::commaSeparated([]));
    }

    public function test_try_from_returns_null_for_undocumented_type(): void
    {
        static::assertNull(KodikMaterialType::tryFrom(self::UNDOCUMENTED_TYPE));
    }
}
