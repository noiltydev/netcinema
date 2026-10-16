<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\Contracts;

use App\Enums\Contracts\MyAnimeListFilterKey;
use App\Enums\MyAnimeList\MyAnimeListCommonFilter;
use App\Enums\MyAnimeList\MyAnimeListSearchFilter;
use PHPUnit\Framework\TestCase;

class MyAnimeListFilterKeyTest extends TestCase
{
    public function test_the_filter_enums_are_filter_keys(): void
    {
        static::assertInstanceOf(MyAnimeListFilterKey::class, MyAnimeListCommonFilter::LIMIT);
        static::assertInstanceOf(MyAnimeListFilterKey::class, MyAnimeListSearchFilter::Q);
    }

    public function test_a_filter_key_reports_the_key_it_writes(): void
    {
        static::assertSame('fields', MyAnimeListCommonFilter::FIELDS->queryKey());
        static::assertSame('offset', MyAnimeListCommonFilter::OFFSET->queryKey());
    }

    public function test_every_case_of_a_filter_enum_is_a_filter_key(): void
    {
        foreach ([...MyAnimeListCommonFilter::cases(), ...MyAnimeListSearchFilter::cases()] as $case) {
            static::assertInstanceOf(MyAnimeListFilterKey::class, $case);
        }
    }
}
