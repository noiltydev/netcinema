<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Values\MyAnimeList\MyAnimeListRankingData;
use App\Values\MyAnimeList\MyAnimeListRankingEntry;
use PHPUnit\Framework\TestCase;

class MyAnimeListRankingDataTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private static function entryPayload(int $id = 30_230, int $rank = 1): array
    {
        return [
            'node' => [
                'id' => $id,
                'title' => 'One Piece',
                'media_type' => 'tv',
                'status' => 'currently_airing',
                'rating' => 'pg_13',
            ],
            'ranking' => ['rank' => $rank],
        ];
    }

    public function test_from_array_maps_entries_and_paging(): void
    {
        $data = MyAnimeListRankingData::fromArray([
            'data' => [self::entryPayload(30_230, 1), self::entryPayload(21, 2)],
            'paging' => [
                'previous' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=2&offset=0',
                'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=2&offset=4',
            ],
        ]);

        static::assertCount(2, $data->entries);
        static::assertInstanceOf(MyAnimeListRankingEntry::class, $data->entries[0]);
        static::assertSame(30_230, $data->entries[0]->anime->id);
        static::assertSame(1, $data->entries[0]->rank);
        static::assertSame(21, $data->entries[1]->anime->id);
        static::assertSame(2, $data->entries[1]->rank);
        static::assertSame(4, $data->nextOffset());
        static::assertSame(0, $data->prevOffset());
        static::assertTrue($data->hasNextPage());
    }

    public function test_from_array_handles_an_empty_page(): void
    {
        $data = MyAnimeListRankingData::fromArray(['data' => [], 'paging' => []]);

        static::assertSame([], $data->entries);
        static::assertNull($data->nextPage);
        static::assertNull($data->prevPage);
        static::assertNull($data->nextOffset());
        static::assertNull($data->prevOffset());
        static::assertFalse($data->hasNextPage());
    }

    public function test_from_array_handles_a_response_missing_its_optional_parts(): void
    {
        $data = MyAnimeListRankingData::fromArray([]);

        static::assertSame([], $data->entries);
        static::assertNull($data->nextPage);
        static::assertFalse($data->hasNextPage());
    }

    public function test_from_array_handles_a_paging_link_that_carries_no_offset(): void
    {
        $data = MyAnimeListRankingData::fromArray([
            'data' => [self::entryPayload()],
            'paging' => ['next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all'],
        ]);

        static::assertNotNull($data->nextPage);
        static::assertNull($data->nextOffset());
        static::assertFalse($data->hasNextPage());
    }

    public function test_from_array_reads_the_previous_rank_when_my_anime_list_sends_it(): void
    {
        $data = MyAnimeListRankingData::fromArray([
            'data' => [
                [
                    'node' => ['id' => 1, 'title' => 'Naruto'],
                    'ranking' => ['rank' => 3, 'previous_rank' => 5],
                ],
            ],
        ]);

        static::assertSame(3, $data->entries[0]->rank);
        static::assertSame(5, $data->entries[0]->previousRank);
    }

    public function test_from_array_leaves_the_previous_rank_null_when_it_is_absent(): void
    {
        $data = MyAnimeListRankingData::fromArray(['data' => [self::entryPayload()]]);

        static::assertSame(1, $data->entries[0]->rank);
        static::assertNull($data->entries[0]->previousRank);
    }

    public function test_from_array_ignores_a_page_entry_of_an_unexpected_shape(): void
    {
        $data = MyAnimeListRankingData::fromArray([
            'data' => [null, 'not-an-object', self::entryPayload()],
            'paging' => 'not-an-object',
        ]);

        static::assertCount(1, $data->entries);
        static::assertSame(30_230, $data->entries[0]->anime->id);
        static::assertNull($data->nextPage);
    }

    public function test_to_array_keeps_the_paging_links_and_the_translated_node(): void
    {
        $ranking = MyAnimeListRankingData::fromArray([
            'data' => [self::entryPayload()],
            'paging' => [
                'previous' => null,
                'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=1&offset=1',
            ],
        ]);

        $array = $ranking->toArray();

        static::assertNull($array['paging']['previous']);
        static::assertSame(
            'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=1&offset=1',
            $array['paging']['next'],
        );
        static::assertSame(['rank' => 1, 'previous_rank' => null], $array['data'][0]['ranking']);
        static::assertSame('ongoing', $array['data'][0]['node']['status']);
    }
}
