<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Integrations\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingData;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingFilters;
use App\Values\MyAnimeList\MyAnimeListFields;
use InvalidArgumentException;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;
use Throwable;

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
                        'id' => 30_230,
                        'title' => 'One Piece',
                        'main_picture' => [
                            'medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                            'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
                        ],
                    ],
                    'ranking' => ['rank' => 1],
                ],
            ],
            'paging' => [
                'previous' => null,
                'next' => 'https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4&offset=4',
            ],
        ];
    }

    public function test_request_uses_correct_endpoint_and_method(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(
            new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL),
        );

        Saloon::assertSent(
            static fn (GetAnimeRankingRequest $request): bool => $request->resolveEndpoint() === '/v2/anime/ranking',
        );

        static::assertTrue($response->successful());
        static::assertSame('GET', $response->getPsrRequest()->getMethod());
    }

    public function test_request_targets_the_versioned_api_base_url(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(
            new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL),
        );

        $url = (string) $response->getPsrRequest()->getUri()->withQuery('');

        static::assertSame('https://api.myanimelist.net/v2/anime/ranking', $url);
    }

    public function test_request_authenticates_with_the_client_id_header(): void
    {
        config(['noilty.services.myanimelist.client_id' => 'CLIENT-ID']);

        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(
            new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL),
        );

        static::assertSame('CLIENT-ID', $response->getPsrRequest()->getHeaderLine('X-MAL-CLIENT-ID'));
    }

    public function test_request_returns_dto(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(self::rankingPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(
            new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL),
        );

        $dto = $response->dtoOrFail();

        static::assertInstanceOf(MyAnimeListAnimeRankingData::class, $dto);
        static::assertCount(1, $dto->data);
        static::assertSame(30_230, $dto->data[0]->node->id);
        static::assertSame(1, $dto->data[0]->ranking->rank);
        static::assertSame(4, $dto->paging->nextOffset());
    }

    public function test_request_sends_the_filters_as_query_params(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['data' => [], 'paging' => ['next' => null]]),
        ]);

        $filters = MyAnimeListAnimeRankingFilters::make()
            ->withLimit(4)
            ->withOffset(8)
            ->withNsfw()
            ->withFields(MyAnimeListFields::fromAnimeNode(
                MyAnimeListAnimeNodeField::ID,
                MyAnimeListAnimeNodeField::TITLE,
            ));

        $response = (new MyAnimeListConnector())->send(
            new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::BYPOPULARITY, $filters->toQuery()),
        );

        parse_str($response->getPsrRequest()->getUri()->getQuery(), $query);

        static::assertSame(
            [
                'ranking_type' => 'bypopularity',
                'limit' => '4',
                'offset' => '8',
                'nsfw' => 'true',
                'fields' => 'id,title',
            ],
            $query,
        );
    }

    public function test_request_sends_ranking_type_alone_when_no_filters_are_set(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['data' => [], 'paging' => ['next' => null]]),
        ]);

        (new MyAnimeListConnector())->send(new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::FAVORITE));

        Saloon::assertSent(
            static fn (GetAnimeRankingRequest $request): bool => $request->query()->all() === [
                'ranking_type' => 'favorite',
            ],
        );
    }

    public function test_request_omits_null_and_empty_filters(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['data' => [], 'paging' => ['next' => null]]),
        ]);

        (new MyAnimeListConnector())->send(new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL, [
            'limit' => 4,
            'offset' => null,
            'fields' => '',
        ]));

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            $query = $request->query();

            return $query->get('limit') === 4 && $query->get('offset') === null && $query->get('fields') === null;
        });
    }

    public function test_request_maps_the_last_page_of_a_ranking(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make([
                'data' => [
                    [
                        'node' => ['id' => 5114, 'title' => 'Higurashi no Naku Koro ni', 'main_picture' => null],
                        'ranking' => ['rank' => 500],
                    ],
                ],
                'paging' => [
                    'previous' => 'https://api.myanimelist.net/v2/anime/ranking?offset=4',
                    'next' => null,
                ],
            ]),
        ]);

        $dto = (new MyAnimeListConnector())
            ->send(new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL))
            ->dtoOrFail();

        static::assertNull($dto->data[0]->node->mainPicture);
        static::assertFalse($dto->paging->hasNextPage());
        static::assertNull($dto->paging->nextOffset());
    }

    public function test_request_throws_forbidden_on_api_error(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make([
                'error' => 'invalid_token',
                'message' => 'token is invalid',
            ], 403),
        ]);

        $this->expectException(ForbiddenException::class);

        (new MyAnimeListConnector())->send(new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL));
    }

    public function test_request_does_not_map_a_payload_missing_the_envelope(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make([
                'data' => [['ranking' => ['rank' => 1]]],
                'paging' => [],
            ]),
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: node');

        (new MyAnimeListConnector())
            ->send(new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL))
            ->dtoOrFail();
    }

    public function test_request_does_not_map_a_body_that_is_not_json(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make('<html>maintenance</html>', 200),
        ]);

        $this->expectException(Throwable::class);

        (new MyAnimeListConnector())
            ->send(new GetAnimeRankingRequest(MyAnimeListRankingTypeAnimeFilter::ALL))
            ->dtoOrFail();
    }
}
