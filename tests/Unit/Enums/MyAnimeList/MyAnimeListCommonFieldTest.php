<?php

declare(strict_types=1);

namespace Tests\Unit\Enums\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListCommonField;
use PHPUnit\Framework\TestCase;

class MyAnimeListCommonFieldTest extends TestCase
{
    public function test_enum_cases_have_expected_values(): void
    {
        static::assertSame('id', MyAnimeListCommonField::ID->value);
        static::assertSame('title', MyAnimeListCommonField::TITLE->value);
        static::assertSame('main_picture', MyAnimeListCommonField::MAIN_PICTURE->value);
        static::assertSame('alternative_titles', MyAnimeListCommonField::ALTERNATIVE_TITLES->value);
        static::assertSame('start_date', MyAnimeListCommonField::START_DATE->value);
        static::assertSame('end_date', MyAnimeListCommonField::END_DATE->value);
        static::assertSame('synopsis', MyAnimeListCommonField::SYNOPSIS->value);
        static::assertSame('mean', MyAnimeListCommonField::MEAN->value);
        static::assertSame('rank', MyAnimeListCommonField::RANK->value);
        static::assertSame('popularity', MyAnimeListCommonField::POPULARITY->value);
        static::assertSame('num_list_users', MyAnimeListCommonField::NUM_LIST_USERS->value);
        static::assertSame('num_scoring_users', MyAnimeListCommonField::NUM_SCORING_USERS->value);
        static::assertSame('nsfw', MyAnimeListCommonField::NSFW->value);
        static::assertSame('created_at', MyAnimeListCommonField::CREATED_AT->value);
        static::assertSame('updated_at', MyAnimeListCommonField::UPDATED_AT->value);
        static::assertSame('media_type', MyAnimeListCommonField::MEDIA_TYPE->value);
        static::assertSame('status', MyAnimeListCommonField::STATUS->value);
        static::assertSame('genres', MyAnimeListCommonField::GENRES->value);
        static::assertSame('my_list_status', MyAnimeListCommonField::MY_LIST_STATUS->value);
        static::assertSame('num_episodes', MyAnimeListCommonField::NUM_EPISODES->value);
        static::assertSame('start_season', MyAnimeListCommonField::START_SEASON->value);
        static::assertSame('broadcast', MyAnimeListCommonField::BROADCAST->value);
        static::assertSame('source', MyAnimeListCommonField::SOURCE->value);
        static::assertSame('average_episode_duration', MyAnimeListCommonField::AVERAGE_EPISODE_DURATION->value);
        static::assertSame('rating', MyAnimeListCommonField::RATING->value);
        static::assertSame('pictures', MyAnimeListCommonField::PICTURES->value);
        static::assertSame('background', MyAnimeListCommonField::BACKGROUND->value);
        static::assertSame('related_anime', MyAnimeListCommonField::RELATED_ANIME->value);
        static::assertSame('related_manga', MyAnimeListCommonField::RELATED_MANGA->value);
        static::assertSame('recommendations', MyAnimeListCommonField::RECOMMENDATIONS->value);
        static::assertSame('studios', MyAnimeListCommonField::STUDIOS->value);
        static::assertSame('statistics', MyAnimeListCommonField::STATISTICS->value);
        static::assertSame('num_volumes', MyAnimeListCommonField::NUM_VOLUMES->value);
        static::assertSame('num_chapters', MyAnimeListCommonField::NUM_CHAPTERS->value);
        static::assertSame('authors', MyAnimeListCommonField::AUTHORS->value);
        static::assertSame('serialization', MyAnimeListCommonField::SERIALIZATION->value);
    }

    public function test_case_names_are_uppercase(): void
    {
        foreach (MyAnimeListCommonField::cases() as $case) {
            static::assertSame(strtoupper($case->value), $case->name);
        }
    }

    public function test_values_returns_all_field_names(): void
    {
        $values = MyAnimeListCommonField::values();

        static::assertContains('id', $values);
        static::assertContains('title', $values);
        static::assertContains('main_picture', $values);
        static::assertContains('alternative_titles', $values);
        static::assertContains('start_date', $values);
        static::assertContains('end_date', $values);
        static::assertContains('synopsis', $values);
        static::assertContains('mean', $values);
        static::assertContains('rank', $values);
        static::assertContains('popularity', $values);
        static::assertContains('num_list_users', $values);
        static::assertContains('num_scoring_users', $values);
        static::assertContains('nsfw', $values);
        static::assertContains('created_at', $values);
        static::assertContains('updated_at', $values);
        static::assertContains('media_type', $values);
        static::assertContains('status', $values);
        static::assertContains('genres', $values);
        static::assertContains('my_list_status', $values);
        static::assertContains('num_episodes', $values);
        static::assertContains('start_season', $values);
        static::assertContains('broadcast', $values);
        static::assertContains('source', $values);
        static::assertContains('average_episode_duration', $values);
        static::assertContains('rating', $values);
        static::assertContains('pictures', $values);
        static::assertContains('background', $values);
        static::assertContains('related_anime', $values);
        static::assertContains('related_manga', $values);
        static::assertContains('recommendations', $values);
        static::assertContains('studios', $values);
        static::assertContains('statistics', $values);
        static::assertContains('num_volumes', $values);
        static::assertContains('num_chapters', $values);
        static::assertContains('authors', $values);
        static::assertContains('serialization', $values);
        static::assertCount(36, $values);
    }
}
