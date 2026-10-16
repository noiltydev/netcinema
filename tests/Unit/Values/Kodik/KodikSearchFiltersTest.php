<?php

declare(strict_types=1);

namespace Tests\Unit\Values\Kodik;

use App\Enums\Kodik\KodikListFilter;
use App\Enums\Kodik\KodikMaterialType;
use App\Enums\Kodik\KodikSearchFilter;
use App\Enums\Kodik\KodikSharedListSearchFilter;
use App\Enums\Kodik\KodikTranslationType;
use App\Values\Kodik\KodikSearchFilters;
use InvalidArgumentException;
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
                'strict' => 'true',
                'full_match' => 'true',
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

    public function test_disabled_boolean_search_builders_send_false(): void
    {
        static::assertSame(
            ['strict' => 'false', 'full_match' => 'false'],
            KodikSearchFilters::make()->withStrictMatch(false)->withFullMatch(false)->toQuery(),
        );
    }

    public function test_search_scope_rejects_the_list_only_sort_and_order(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: sort');

        KodikSearchFilters::make()->with(KodikListFilter::SORT, 'updated_at');
    }

    public function test_search_scope_rejects_the_list_only_order(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: order');

        KodikSearchFilters::make()->with(KodikListFilter::ORDER, 'asc');
    }

    public function test_search_scope_accepts_the_shared_and_search_only_filters(): void
    {
        $query = KodikSearchFilters::make()
            ->withLimit(100)
            ->withTypes(KodikMaterialType::ANIME_SERIAL)
            ->with(KodikSharedListSearchFilter::WITH_MATERIAL_DATA, true)
            ->with(KodikSearchFilter::PLAYER_LINK, 'https://kodikplayer.com/video/19850')
            ->toQuery();

        static::assertSame(
            [
                'limit' => 100,
                'types' => 'anime-serial',
                'with_material_data' => 'true',
                'player_link' => 'https://kodikplayer.com/video/19850',
            ],
            $query,
        );
    }

    public function test_external_id_builders_write_their_own_keys(): void
    {
        static::assertSame(
            [
                'kinopoisk_id' => '43949',
                'imdb_id' => 'tt0084716',
                'mdl_id' => '12345-title',
                'worldart_animation_id' => '111',
                'worldart_cinema_id' => '222',
                'worldart_link' => 'https://worldart.org/Anime/111',
                'shikimori_id' => '61240',
            ],
            KodikSearchFilters::make()
                ->withKinopoiskId('43949')
                ->withImdbId('tt0084716')
                ->withMdlId('12345-title')
                ->withWorldartAnimationId('111')
                ->withWorldartCinemaId('222')
                ->withWorldartLink('https://worldart.org/Anime/111')
                ->withShikimoriId('61240')
                ->toQuery(),
        );
    }

    public function test_translation_priority_builders_accept_ids_and_the_type_shorthand(): void
    {
        static::assertSame(
            ['prioritize_translations' => '704,734,subtitles'],
            KodikSearchFilters::make()->withPrioritizeTranslations(704, 734, 'subtitles')->toQuery(),
        );

        static::assertSame(
            ['unprioritize_translations' => '0'],
            KodikSearchFilters::make()->withUnprioritizeTranslations(0)->toQuery(),
        );

        static::assertSame(
            ['prioritize_translation_type' => 'subtitles'],
            KodikSearchFilters::make()->withPrioritizeTranslationType(KodikTranslationType::SUBTITLES)->toQuery(),
        );
    }

    public function test_with_episode_is_rejected_without_a_season(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Episode requires a season, call withSeason() first.');

        KodikSearchFilters::make()->withEpisode(5);
    }

    public function test_with_episode_is_accepted_after_a_season(): void
    {
        $query = KodikSearchFilters::make()->withSeason(2)->withEpisode(5)->toQuery();

        static::assertSame(['season' => 2, 'episode' => 5], $query);
    }

    public function test_with_limit_rejects_a_value_outside_the_documented_range(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100, got 101.');

        KodikSearchFilters::make()->withLimit(101);
    }

    public function test_boolean_search_filter_is_normalized(): void
    {
        $query = KodikSearchFilters::make()->with(KodikSearchFilter::STRICT, true)->toQuery();

        static::assertSame(['strict' => 'true'], $query);
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
