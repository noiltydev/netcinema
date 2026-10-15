<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListNsfw;
use PHPUnit\Framework\TestCase;

class MyAnimeListNsfwTest extends TestCase
{
    public function test_the_values_are_the_three_state_rating_rather_than_a_boolean(): void
    {
        static::assertSame('white', MyAnimeListNsfw::SAFE->value);
        static::assertSame('gray', MyAnimeListNsfw::MAY_BE_UNSAFE->value);
        static::assertSame('black', MyAnimeListNsfw::UNSAFE->value);
    }

    public function test_values_lists_them_in_the_documented_order(): void
    {
        static::assertSame(['white', 'gray', 'black'], MyAnimeListNsfw::values());
    }

    public function test_values_covers_every_case(): void
    {
        static::assertCount(count(MyAnimeListNsfw::cases()), MyAnimeListNsfw::values());
    }
}
