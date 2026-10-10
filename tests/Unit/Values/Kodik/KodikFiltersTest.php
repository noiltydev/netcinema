<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikStatus;
use App\Values\Kodik\KodikFilters;
use App\Values\Kodik\KodikListFilters;
use App\Values\Kodik\KodikSearchFilters;
use App\Values\Kodik\KodikTranslationsFilters;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class KodikFiltersTest extends TestCase
{
    public function test_with_types_joins_the_values_with_commas(): void
    {
        $query = KodikTranslationsFilters::make()
            ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
            ->toQuery();

        static::assertSame(['types' => 'anime,anime-serial'], $query);
    }

    public function test_with_field_writes_the_has_field_key(): void
    {
        $query = KodikTranslationsFilters::make()
            ->withField(KodikMaterialField::SHIKIMORI_ID, KodikMaterialField::KINOPOISK_ID)
            ->toQuery();

        static::assertSame(['has_field' => 'shikimori_id,kinopoisk_id'], $query);
    }

    public function test_with_anime_kind_writes_the_anime_kind_key(): void
    {
        $query = KodikTranslationsFilters::make()->withAnimeKind(KodikAnimeKind::TV, KodikAnimeKind::TV_24)->toQuery();

        static::assertSame(['anime_kind' => 'tv,tv_24'], $query);
    }

    public function test_status_filters_write_their_own_keys_from_one_shared_vocabulary(): void
    {
        static::assertSame(
            ['anime_status' => 'released'],
            KodikTranslationsFilters::make()->withAnimeStatus(KodikStatus::RELEASED)->toQuery(),
        );

        static::assertSame(
            ['drama_status' => 'ongoing'],
            KodikTranslationsFilters::make()->withDramaStatus(KodikStatus::ONGOING)->toQuery(),
        );

        static::assertSame(
            ['all_status' => 'anons,released'],
            KodikTranslationsFilters::make()->withAnyStatus(KodikStatus::ANONS, KodikStatus::RELEASED)->toQuery(),
        );
    }

    public function test_with_rating_mpaa_preserves_the_wire_values(): void
    {
        $query = KodikTranslationsFilters::make()
            ->withRatingMpaa(KodikRatingMpaa::PG_13, KodikRatingMpaa::R_PLUS, KodikRatingMpaa::RX)
            ->toQuery();

        static::assertSame(['rating_mpaa' => 'PG-13,R+,Rx'], $query);
    }

    public function test_shared_chain_produces_the_expected_query(): void
    {
        $query = KodikTranslationsFilters::make()
            ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
            ->withField(KodikMaterialField::SHIKIMORI_ID)
            ->withAnimeKind(KodikAnimeKind::TV, KodikAnimeKind::TV_24)
            ->withAnimeStatus(KodikStatus::RELEASED)
            ->withDramaStatus(KodikStatus::ONGOING)
            ->withAnyStatus(KodikStatus::ANONS)
            ->withRatingMpaa(KodikRatingMpaa::PG_13)
            ->toQuery();

        static::assertSame(
            [
                'types' => 'anime,anime-serial',
                'has_field' => 'shikimori_id',
                'anime_kind' => 'tv,tv_24',
                'anime_status' => 'released',
                'drama_status' => 'ongoing',
                'all_status' => 'anons',
                'rating_mpaa' => 'PG-13',
            ],
            $query,
        );
    }

    public function test_shared_filters_are_available_in_every_scope(): void
    {
        static::assertSame(
            ['sort' => 'title'],
            KodikTranslationsFilters::make()->with(KodikFilter::SORT, 'title')->toQuery(),
        );

        static::assertSame(
            ['sort' => 'updated_at'],
            KodikListFilters::make()->with(KodikFilter::SORT, 'updated_at')->toQuery(),
        );

        static::assertSame(
            ['sort' => 'updated_at'],
            KodikSearchFilters::make()->with(KodikFilter::SORT, 'updated_at')->toQuery(),
        );
    }

    public function test_builder_is_immutable(): void
    {
        $base = KodikListFilters::make()->withLimit(50);
        $derived = $base->withTypes(KodikMaterialType::ANIME);

        static::assertSame(['limit' => 50], $base->toQuery());
        static::assertSame(['limit' => 50, 'types' => 'anime'], $derived->toQuery());
    }

    public function test_chaining_preserves_the_concrete_scope(): void
    {
        static::assertInstanceOf(
            KodikTranslationsFilters::class,
            KodikTranslationsFilters::make()->withTypes(KodikMaterialType::ANIME),
        );

        static::assertInstanceOf(
            KodikListFilters::class,
            KodikListFilters::make()->withTypes(KodikMaterialType::ANIME),
        );

        static::assertInstanceOf(
            KodikSearchFilters::class,
            KodikSearchFilters::make()->withTypes(KodikMaterialType::ANIME),
        );
    }

    public function test_key_outside_the_scope_is_rejected(): void
    {
        $filters = new readonly class([], []) extends KodikFilters {
            /**
             * @param array<string, string|int> $query
             * @param array<string> $allowedKeys
             */
            public function __construct(array $query, array $allowedKeys)
            {
                parent::__construct($query, $allowedKeys);
            }

            public function with(KodikFilter $key, string|int|bool $value): static
            {
                return $this->put($key, $value);
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: year');

        $filters->with(KodikFilter::YEAR, '2023');
    }
}
