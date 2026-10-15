<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\MyAnimeList\MyAnimeListRankingType;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Services\MyAnimeListService;
use App\Values\MyAnimeList\MyAnimeListRankingData;
use App\Values\MyAnimeList\MyAnimeListRankingFilters;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class MyAnimeListServiceTest extends TestCase
{
    /**
     * @param array<string, mixed> $body
     */
    private static function fakeRanking(array $body = []): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(
                $body
                ?: [
                    'data' => [
                        [
                            'node' => ['id' => 52_991, 'title' => 'Sousou no Frieren', 'media_type' => 'tv'],
                            'ranking' => ['rank' => 1],
                        ],
                    ],
                    'paging' => ['next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&offset=1'],
                ],
            ),
        ]);
    }

    public function test_get_anime_ranking_returns_a_dto(): void
    {
        self::fakeRanking();

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $result = $service->getAnimeRanking();

        static::assertInstanceOf(MyAnimeListRankingData::class, $result);
        static::assertCount(1, $result->entries);
        static::assertSame(52_991, $result->entries[0]->anime->id);
        static::assertSame(1, $result->nextOffset());
    }

    public function test_get_anime_ranking_defaults_to_the_overall_ranking(): void
    {
        self::fakeRanking();

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $service->getAnimeRanking();

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->query()->get('ranking_type') === 'all';
        });
    }

    public function test_get_anime_ranking_passes_the_ranking_type_to_the_request(): void
    {
        self::fakeRanking();

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $service->getAnimeRanking(rankingType: MyAnimeListRankingType::AIRING);

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->query()->get('ranking_type') === 'airing';
        });
    }

    public function test_get_anime_ranking_passes_filters_to_the_request(): void
    {
        self::fakeRanking();

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $service->getAnimeRanking(
            MyAnimeListRankingFilters::make()
                ->withLimit(500)
                ->withFields('id', 'title', 'media_type', 'status', 'rating')
                ->withNsfw(),
        );

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return (
                $request->query()->get('limit') === 500
                && $request->query()->get('fields') === 'id,title,media_type,status,rating'
                && $request->query()->get('nsfw') === 'true'
            );
        });
    }

    public function test_get_anime_ranking_with_no_filters_sends_no_optional_params(): void
    {
        self::fakeRanking();

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $service->getAnimeRanking();

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return (
                $request->query()->get('fields') === null
                && $request->query()->get('limit') === null
                && $request->query()->get('nsfw') === null
                && $request->query()->get('offset') === null
            );
        });
    }

    public function test_get_anime_ranking_passes_the_offset_to_the_request(): void
    {
        self::fakeRanking();

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $service->getAnimeRanking(MyAnimeListRankingFilters::make()->withLimit(100), offset: 200);

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->query()->get('offset') === 200;
        });
    }

    public function test_get_anime_ranking_walks_the_whole_listing_from_the_returned_offsets(): void
    {
        Saloon::fake([
            MockResponse::make([
                'data' => [['node' => ['id' => 1, 'title' => 'First'], 'ranking' => ['rank' => 1]]],
                'paging' => [
                    'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=1&offset=1',
                ],
            ]),
            MockResponse::make([
                'data' => [['node' => ['id' => 2, 'title' => 'Second'], 'ranking' => ['rank' => 2]]],
                'paging' => [],
            ]),
        ]);

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $filters = MyAnimeListRankingFilters::make()->withLimit(1);

        $titles = [];
        $response = $service->getAnimeRanking($filters);

        while (true) {
            foreach ($response->entries as $entry) {
                $titles[] = $entry->anime->title;
            }

            if (!$response->hasNextPage()) {
                break;
            }

            $response = $service->getAnimeRanking($filters, offset: $response->nextOffset());
        }

        static::assertSame(['First', 'Second'], $titles);
    }

    public function test_get_anime_ranking_returns_an_empty_dto_for_an_empty_listing(): void
    {
        self::fakeRanking(['data' => [], 'paging' => []]);

        $service = new MyAnimeListService(new MyAnimeListConnector());
        $result = $service->getAnimeRanking();

        static::assertInstanceOf(MyAnimeListRankingData::class, $result);
        static::assertSame([], $result->entries);
        static::assertFalse($result->hasNextPage());
    }

    public function test_get_anime_ranking_propagates_an_api_error(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['message' => 'DoS detected'], 403),
        ]);

        $service = new MyAnimeListService(new MyAnimeListConnector());

        $this->expectException(ForbiddenException::class);

        $service->getAnimeRanking();
    }
}
