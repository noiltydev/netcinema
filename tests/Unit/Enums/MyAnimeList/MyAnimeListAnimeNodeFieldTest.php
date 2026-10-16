<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeNodeFieldTest extends TestCase
{
    public function test_enum_cases_match_documented_fields(): void
    {
        static::assertSame('id', MyAnimeListAnimeNodeField::ID->value);
        static::assertSame('title', MyAnimeListAnimeNodeField::TITLE->value);
        static::assertSame('main_picture', MyAnimeListAnimeNodeField::MAIN_PICTURE->value);
        static::assertSame('alternative_titles', MyAnimeListAnimeNodeField::ALTERNATIVE_TITLES->value);
        static::assertSame('start_date', MyAnimeListAnimeNodeField::START_DATE->value);
        static::assertSame('end_date', MyAnimeListAnimeNodeField::END_DATE->value);
        static::assertSame('synopsis', MyAnimeListAnimeNodeField::SYNOPSIS->value);
        static::assertSame('mean', MyAnimeListAnimeNodeField::MEAN->value);
        static::assertSame('rank', MyAnimeListAnimeNodeField::RANK->value);
        static::assertSame('popularity', MyAnimeListAnimeNodeField::POPULARITY->value);
        static::assertSame('num_list_users', MyAnimeListAnimeNodeField::NUM_LIST_USERS->value);
        static::assertSame('num_scoring_users', MyAnimeListAnimeNodeField::NUM_SCORING_USERS->value);
        static::assertSame('nsfw', MyAnimeListAnimeNodeField::NSFW->value);
        static::assertSame('genres', MyAnimeListAnimeNodeField::GENRES->value);
        static::assertSame('created_at', MyAnimeListAnimeNodeField::CREATED_AT->value);
        static::assertSame('updated_at', MyAnimeListAnimeNodeField::UPDATED_AT->value);
        static::assertSame('media_type', MyAnimeListAnimeNodeField::MEDIA_TYPE->value);
        static::assertSame('status', MyAnimeListAnimeNodeField::STATUS->value);
        static::assertSame('my_list_status', MyAnimeListAnimeNodeField::MY_LIST_STATUS->value);
        static::assertSame('num_episodes', MyAnimeListAnimeNodeField::NUM_EPISODES->value);
        static::assertSame('start_season', MyAnimeListAnimeNodeField::START_SEASON->value);
        static::assertSame('broadcast', MyAnimeListAnimeNodeField::BROADCAST->value);
        static::assertSame('source', MyAnimeListAnimeNodeField::SOURCE->value);
        static::assertSame('average_episode_duration', MyAnimeListAnimeNodeField::AVERAGE_EPISODE_DURATION->value);
        static::assertSame('rating', MyAnimeListAnimeNodeField::RATING->value);
        static::assertSame('studios', MyAnimeListAnimeNodeField::STUDIOS->value);
    }

    public function test_values_returns_every_documented_anime_node_field(): void
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
                'num_episodes',
                'start_season',
                'broadcast',
                'source',
                'average_episode_duration',
                'rating',
                'studios',
            ],
            MyAnimeListAnimeNodeField::values(),
        );
    }

    public function test_the_node_fields_exclude_the_details_only_fields(): void
    {
        foreach (['pictures', 'background', 'related_anime', 'statistics'] as $detailsField) {
            static::assertNotContains($detailsField, MyAnimeListAnimeNodeField::values());
        }
    }

    public function test_the_node_fields_exclude_the_manga_only_fields(): void
    {
        foreach (['num_volumes', 'num_chapters', 'authors', 'serialization'] as $mangaField) {
            static::assertNotContains($mangaField, MyAnimeListAnimeNodeField::values());
        }
    }
}
