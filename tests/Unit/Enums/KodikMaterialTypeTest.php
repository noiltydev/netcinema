<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikMaterialType;
use PHPUnit\Framework\TestCase;

class KodikMaterialTypeTest extends TestCase
{
    public function test_enum_cases_match_documented_vocabulary(): void
    {
        $this->assertSame('foreign-movie', KodikMaterialType::FOREIGN_MOVIE->value);
        $this->assertSame('soviet-cartoon', KodikMaterialType::SOVIET_CARTOON->value);
        $this->assertSame('foreign-cartoon', KodikMaterialType::FOREIGN_CARTOON->value);
        $this->assertSame('russian-cartoon', KodikMaterialType::RUSSIAN_CARTOON->value);
        $this->assertSame('anime', KodikMaterialType::ANIME->value);
        $this->assertSame('russian-movie', KodikMaterialType::RUSSIAN_MOVIE->value);
        $this->assertSame('cartoon-serial', KodikMaterialType::CARTOON_SERIAL->value);
        $this->assertSame('documentary-serial', KodikMaterialType::DOCUMENTARY_SERIAL->value);
        $this->assertSame('russian-serial', KodikMaterialType::RUSSIAN_SERIAL->value);
        $this->assertSame('foreign-serial', KodikMaterialType::FOREIGN_SERIAL->value);
        $this->assertSame('anime-serial', KodikMaterialType::ANIME_SERIAL->value);
        $this->assertSame('multi-part-film', KodikMaterialType::MULTI_PART_FILM->value);
    }

    public function test_values_returns_every_material_type(): void
    {
        $this->assertSame([
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
        ], KodikMaterialType::values());
    }

    public function test_comma_separated_joins_types_for_the_filter_value(): void
    {
        $joined = KodikMaterialType::commaSeparated([
            KodikMaterialType::ANIME,
            KodikMaterialType::ANIME_SERIAL,
        ]);

        $this->assertSame('anime,anime-serial', $joined);
    }

    public function test_comma_separated_returns_empty_string_without_types(): void
    {
        $this->assertSame('', KodikMaterialType::commaSeparated([]));
    }

    public function test_try_from_returns_null_for_undocumented_type(): void
    {
        $this->assertNull(KodikMaterialType::tryFrom('documentary-film'));
    }
}
