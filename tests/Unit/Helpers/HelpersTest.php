<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_slugify_replaces_ampersand_with_and(): void
    {
        static::assertSame('rock-and-roll', slugify('Rock & Roll'));
    }

    public function test_slugify_replaces_adult_marker_with_r_plus(): void
    {
        static::assertSame('a-r-plus-b', slugify('A 18+ B'));
    }

    public function test_slugify_transliterates_cyrillic(): void
    {
        static::assertSame('nazvanie', slugify('Название'));
    }

    public function test_slugify_is_empty_for_title_without_transliterable_characters(): void
    {
        static::assertSame('', slugify('!!!'));
    }

    public function test_simple_hash_is_stable_and_namespaced(): void
    {
        $hash = simple_hash('funteam');

        static::assertSame($hash, simple_hash('funteam'));
        static::assertNotSame($hash, simple_hash('other'));
        static::assertSame(md5('noilty:funteam'), $hash);
    }

    public function test_simple_hash_handles_null(): void
    {
        static::assertSame(md5('noilty:'), simple_hash(null));
    }

    public function test_cache_key_joins_parts_into_a_namespaced_hash(): void
    {
        static::assertSame(simple_hash('funteam.2'), cache_key('funteam', 2));
    }
}
