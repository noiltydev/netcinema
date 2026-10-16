<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListMangaNodeField;
use PHPUnit\Framework\TestCase;

class MyAnimeListMangaNodeFieldTest extends TestCase
{
    public function test_enum_cases_match_documented_fields(): void
    {
        static::assertSame('id', MyAnimeListMangaNodeField::ID->value);
        static::assertSame('title', MyAnimeListMangaNodeField::TITLE->value);
        static::assertSame('main_picture', MyAnimeListMangaNodeField::MAIN_PICTURE->value);
        static::assertSame('alternative_titles', MyAnimeListMangaNodeField::ALTERNATIVE_TITLES->value);
        static::assertSame('start_date', MyAnimeListMangaNodeField::START_DATE->value);
        static::assertSame('end_date', MyAnimeListMangaNodeField::END_DATE->value);
        static::assertSame('synopsis', MyAnimeListMangaNodeField::SYNOPSIS->value);
        static::assertSame('mean', MyAnimeListMangaNodeField::MEAN->value);
        static::assertSame('rank', MyAnimeListMangaNodeField::RANK->value);
        static::assertSame('popularity', MyAnimeListMangaNodeField::POPULARITY->value);
        static::assertSame('num_list_users', MyAnimeListMangaNodeField::NUM_LIST_USERS->value);
        static::assertSame('num_scoring_users', MyAnimeListMangaNodeField::NUM_SCORING_USERS->value);
        static::assertSame('nsfw', MyAnimeListMangaNodeField::NSFW->value);
        static::assertSame('genres', MyAnimeListMangaNodeField::GENRES->value);
        static::assertSame('created_at', MyAnimeListMangaNodeField::CREATED_AT->value);
        static::assertSame('updated_at', MyAnimeListMangaNodeField::UPDATED_AT->value);
        static::assertSame('media_type', MyAnimeListMangaNodeField::MEDIA_TYPE->value);
        static::assertSame('status', MyAnimeListMangaNodeField::STATUS->value);
        static::assertSame('my_list_status', MyAnimeListMangaNodeField::MY_LIST_STATUS->value);
        static::assertSame('num_volumes', MyAnimeListMangaNodeField::NUM_VOLUMES->value);
        static::assertSame('num_chapters', MyAnimeListMangaNodeField::NUM_CHAPTERS->value);
        static::assertSame('authors', MyAnimeListMangaNodeField::AUTHORS->value);
    }

    public function test_values_returns_every_documented_manga_node_field(): void
    {
        static::assertSame(
            [
                'id',
                'title',
                'main_picture',
                'alternative_titles',
                'start_date',
                'end_date',
                'synopsis',
                'mean',
                'rank',
                'popularity',
                'num_list_users',
                'num_scoring_users',
                'nsfw',
                'genres',
                'created_at',
                'updated_at',
                'media_type',
                'status',
                'my_list_status',
                'num_volumes',
                'num_chapters',
                'authors',
            ],
            MyAnimeListMangaNodeField::values(),
        );
    }

    public function test_the_node_fields_exclude_the_anime_only_fields(): void
    {
        foreach (['num_episodes', 'start_season', 'broadcast', 'studios'] as $animeField) {
            static::assertNotContains($animeField, MyAnimeListMangaNodeField::values());
        }
    }

    public function test_the_node_fields_exclude_the_details_only_fields(): void
    {
        foreach (['pictures', 'background', 'related_manga', 'serialization'] as $detailsField) {
            static::assertNotContains($detailsField, MyAnimeListMangaNodeField::values());
        }
    }
}
