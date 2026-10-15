<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Integrations\MyAnimeList;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Enums\MyAnimeList\MyAnimeListNsfw;
use App\Enums\MyAnimeList\MyAnimeListRankingType;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Values\MyAnimeList\MyAnimeListAnime;
use App\Values\MyAnimeList\MyAnimeListRankingData;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class GetAnimeRankingRequestTest extends TestCase
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
                        'id' => 52_991,
                        'title' => 'Sousou no Frieren',
                        'main_picture' => [
                            'medium' => 'https://cdn.myanimelist.net/images/anime/1015/138006.jpg',
                            'large' => 'https://cdn.myanimelist.net/images/anime/1015/138006l.jpg',
                        ],
                        'start_date' => '2023-09-29',
                        'end_date' => '2024-03-22',
                        'mean' => 9.24,
                        'media_type' => 'tv',
                        'status' => 'finished_airing',
                        'genres' => [['id' => 2, 'name' => 'Adventure']],
                        'num_list_users' => 1_530_489,
                        'nsfw' => 'white',
                        'num_episodes' => 28,
                        'start_season' => ['year' => 2023, 'season' => 'fall'],
                        'rating' => 'pg_13',
                    ],
                    'ranking' => ['rank' => 1],
                ],
            ],
            'paging' => [
                'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=1&offset=1',
            ],
        ];
    }

    public function test_request_uses_correct_endpoint_and_method(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $response = $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->resolveEndpoint() === '/v2/anime/ranking' && $request->getMethod()->value === 'GET';
        });

        static::assertTrue($response->successful());
    }

    public function test_request_always_sends_the_required_ranking_type(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::BY_POPULARITY));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->query()->get('ranking_type') === 'bypopularity';
        });
    }

    public function test_request_sends_filters_as_query_params(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL, [
            'limit' => 500,
            'fields' => 'id,title,media_type',
            'nsfw' => 'true',
        ]));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return (
                $request->query()->get('limit') === 500
                && $request->query()->get('fields') === 'id,title,media_type'
                && $request->query()->get('nsfw') === 'true'
            );
        });
    }

    public function test_request_sends_the_offset_when_paging(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL, ['limit' => 100], 200));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->query()->get('offset') === 200 && $request->query()->get('limit') === 100;
        });
    }

    public function test_request_omits_the_offset_on_the_first_page(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return $request->query()->get('offset') === null;
        });
    }

    public function test_request_omits_null_and_empty_filters(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL, [
            'fields' => 'id,title',
            'source' => null,
            'synopsis' => '',
        ]));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            return (
                $request->query()->get('fields') === 'id,title'
                && $request->query()->get('source') === null
                && $request->query()->get('synopsis') === null
            );
        });
    }

    public function test_request_returns_a_dto(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $response = $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));

        $dto = $response->dtoOrFail();

        static::assertInstanceOf(MyAnimeListRankingData::class, $dto);
        static::assertCount(1, $dto->entries);
        static::assertSame(1, $dto->entries[0]->rank);
        static::assertNull($dto->entries[0]->previousRank);
        static::assertSame(1, $dto->nextOffset());
        static::assertTrue($dto->hasNextPage());
    }

    public function test_request_maps_the_node_into_domain_vocabulary(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $connector = new MyAnimeListConnector();
        $response = $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));

        $anime = $response->dtoOrFail()->entries[0]->anime;

        static::assertInstanceOf(MyAnimeListAnime::class, $anime);
        static::assertSame(52_991, $anime->id);
        static::assertSame('Sousou no Frieren', $anime->title);
        static::assertSame(AnimeKind::TV, $anime->kind);
        static::assertSame(EntryStatus::RELEASED, $anime->status);
        static::assertSame(EntryRating::PG_13, $anime->rating);
        static::assertSame(EntrySeason::FALL, $anime->startSeason);
        static::assertSame(2023, $anime->startSeasonYear);
        static::assertSame(MyAnimeListNsfw::SAFE, $anime->nsfw);
        static::assertSame('2023-09-29', $anime->startedAt?->toDateString());
        static::assertSame('2024-03-22', $anime->endedAt?->toDateString());
        static::assertSame(28, $anime->numEpisodes);
    }

    public function test_request_returns_an_empty_dto_for_an_empty_ranking(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['data' => [], 'paging' => []]),
        ]);

        $connector = new MyAnimeListConnector();
        $response = $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));

        $dto = $response->dtoOrFail();

        static::assertInstanceOf(MyAnimeListRankingData::class, $dto);
        static::assertSame([], $dto->entries);
        static::assertFalse($dto->hasNextPage());
    }

    public function test_request_returns_a_dto_for_the_bare_field_set_my_anime_list_defaults_to(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make([
                'data' => [
                    [
                        'node' => [
                            'id' => 5114,
                            'title' => 'Fullmetal Alchemist: Brotherhood',
                            'main_picture' => [
                                'medium' => 'https://cdn.myanimelist.net/images/anime/1200/94745.webp',
                            ],
                        ],
                        'ranking' => ['rank' => 2],
                    ],
                ],
            ]),
        ]);

        $connector = new MyAnimeListConnector();
        $response = $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));

        $anime = $response->dtoOrFail()->entries[0]->anime;

        static::assertSame(5114, $anime->id);
        static::assertNull($anime->kind);
        static::assertNull($anime->status);
        static::assertNull($anime->rating);
    }

    public function test_request_throws_unauthorized_on_an_invalid_token(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make([
                'error' => 'invalid_token',
                'message' => 'token is invalid',
            ], 401),
        ]);

        $connector = new MyAnimeListConnector();

        $this->expectException(UnauthorizedException::class);

        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));
    }

    public function test_request_throws_forbidden_on_an_api_error(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['message' => 'DoS detected'], 403),
        ]);

        $connector = new MyAnimeListConnector();

        $this->expectException(ForbiddenException::class);

        $connector->send(new GetAnimeRankingRequest(MyAnimeListRankingType::ALL));
    }
}
