<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Values\MyAnimeList\MyAnimeListAnimeNode;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingData;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeRankingDataTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private static function rankingPayload(): array
    {
        return [
            'data' => [
                [
                    'node' => [
                        'id' => 30_230,
                        'title' => 'One Piece',
                        'main_picture' => [
                            'medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                            'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
                        ],
                    ],
                    'ranking' => ['rank' => 1, 'previous_rank' => 2],
                ],
            ],
            'paging' => [
                'previous' => null,
                'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4&offset=4',
            ],
        ];
    }

    public function test_from_array_maps_the_response_envelope(): void
    {
        $data = MyAnimeListAnimeRankingData::fromArray(self::rankingPayload());

        static::assertCount(1, $data->data);
        static::assertInstanceOf(MyAnimeListAnimeNode::class, $data->data[0]);
        static::assertSame(30_230, $data->data[0]->node->id);
        static::assertSame(1, $data->data[0]->ranking->rank);
        static::assertSame(2, $data->data[0]->ranking->previousRank);
        static::assertTrue($data->paging->hasNextPage());
    }

    public function test_from_array_maps_an_empty_payload(): void
    {
        $data = MyAnimeListAnimeRankingData::fromArray(['data' => [], 'paging' => ['next' => null]]);

        static::assertSame([], $data->data);
        static::assertFalse($data->paging->hasNextPage());
    }

    public function test_from_array_maps_a_payload_without_the_envelope(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: data');

        MyAnimeListAnimeRankingData::fromArray([]);
    }

    public function test_from_array_maps_a_payload_without_paging(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: paging');

        MyAnimeListAnimeRankingData::fromArray(['data' => []]);
    }

    public function test_from_array_rejects_an_entry_without_a_node(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: node');

        MyAnimeListAnimeRankingData::fromArray([
            'data' => [['ranking' => ['rank' => 1]]],
            'paging' => ['next' => null],
        ]);
    }

    public function test_from_array_rejects_a_node_without_an_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: id');

        MyAnimeListAnimeRankingData::fromArray([
            'data' => [['node' => ['title' => 'One Piece'], 'ranking' => ['rank' => 1]]],
            'paging' => ['next' => null],
        ]);
    }

    public function test_previous_rank_stays_null_when_the_api_omits_it(): void
    {
        $data = MyAnimeListAnimeRankingData::fromArray([
            'data' => [['node' => ['id' => 1, 'title' => 'One Piece'], 'ranking' => ['rank' => 7]]],
            'paging' => [],
        ]);

        static::assertSame(7, $data->data[0]->ranking->rank);
        static::assertNull($data->data[0]->ranking->previousRank);
    }

    public function test_to_array_returns_the_api_shape(): void
    {
        static::assertSame(
            [
                'data' => [
                    [
                        'node' => [
                            'id' => 30_230,
                            'title' => 'One Piece',
                            'main_picture' => [
                                'medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                                'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
                            ],
                            'alternative_titles' => null,
                        ],
                        'ranking' => ['rank' => 1, 'previous_rank' => 2],
                    ],
                ],
                'paging' => [
                    'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4&offset=4',
                    'previous' => null,
                ],
            ],
            MyAnimeListAnimeRankingData::fromArray(self::rankingPayload())->toArray(),
        );
    }

    public function test_a_ranking_entry_round_trips_through_to_array(): void
    {
        $data = MyAnimeListAnimeRankingData::fromArray(self::rankingPayload());

        static::assertSame($data->toArray(), MyAnimeListAnimeRankingData::fromArray($data->toArray())->toArray());
    }
}
