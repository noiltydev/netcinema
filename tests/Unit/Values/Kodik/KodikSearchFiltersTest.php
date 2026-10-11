<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialType;
use App\Enums\KodikSearchFilter;
use App\Values\Kodik\KodikSearchFilters;
use PHPUnit\Framework\TestCase;

class KodikSearchFiltersTest extends TestCase
{
    public function test_empty_scope_produces_an_empty_query(): void
    {
        static::assertSame([], KodikSearchFilters::make()->toQuery());
    }

    public function test_search_only_named_builders_write_their_keys(): void
    {
        static::assertSame(
            [
                'title' => 'Спортлото-82',
                'title_orig' => 'Спортлото-82',
                'strict' => '1',
                'full_match' => '1',
                'id' => 'movie-452654',
                'kinopoisk_id' => '43949',
                'imdb_id' => 'tt0084716',
                'shikimori_id' => '12345',
                'season' => 2,
                'episode' => 5,
            ],
            KodikSearchFilters::make()
                ->withTitle('Спортлото-82')
                ->withTitleOrig('Спортлото-82')
                ->withStrictMatch()
                ->withFullMatch()
                ->withKodikId('movie-452654')
                ->withKinopoiskId('43949')
                ->withImdbId('tt0084716')
                ->withShikimoriId('12345')
                ->withSeason(2)
                ->withEpisode(5)
                ->toQuery(),
        );
    }

    public function test_disabled_boolean_search_builders_send_zero(): void
    {
        static::assertSame(
            ['strict' => '0', 'full_match' => '0'],
            KodikSearchFilters::make()->withStrictMatch(false)->withFullMatch(false)->toQuery(),
        );
    }

    public function test_search_scope_accepts_shared_and_list_filters(): void
    {
        $query = KodikSearchFilters::make()
            ->withLimit(100)
            ->withTypes(KodikMaterialType::ANIME_SERIAL)
            ->with(KodikFilter::SORT, 'updated_at')
            ->with(KodikListFilter::ORDER, 'asc')
            ->with(KodikSearchFilter::PLAYER_LINK, 'https://kodikplayer.com/video/19850')
            ->toQuery();

        static::assertSame(
            [
                'limit' => 100,
                'types' => 'anime-serial',
                'sort' => 'updated_at',
                'order' => 'asc',
                'player_link' => 'https://kodikplayer.com/video/19850',
            ],
            $query,
        );
    }

    public function test_boolean_search_filter_is_normalized(): void
    {
        $query = KodikSearchFilters::make()->with(KodikSearchFilter::STRICT, true)->toQuery();

        static::assertSame(['strict' => '1'], $query);
    }

    public function test_with_translation_id_joins_the_ids_with_commas(): void
    {
        $query = KodikSearchFilters::make()->withTranslationId(714, 720)->toQuery();

        static::assertSame(['translation_id' => '714,720'], $query);
    }

    public function test_with_translation_id_is_immutable(): void
    {
        $base = KodikSearchFilters::make()->withTitle('Спортлото-82');
        $derived = $base->withTranslationId(714);

        static::assertSame(['title' => 'Спортлото-82'], $base->toQuery());
        static::assertSame(['title' => 'Спортлото-82', 'translation_id' => '714'], $derived->toQuery());
    }
}
