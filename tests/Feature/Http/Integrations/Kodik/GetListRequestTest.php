<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Integrations\Kodik;

use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetListRequest;
use App\Values\Kodik\KodikListData;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class GetListRequestTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private static function materialPayload(): array
    {
        return [
            'id' => 'movie-452654',
            'type' => 'foreign-movie',
            'link' => 'http://kodikplayer.com/video/19850/6476310cc6d90aa9304d5d8af3a91279/720p',
            'title' => 'Спортлото-82',
            'title_orig' => 'Спортлото-82',
            'translation' => [
                'id' => 703,
                'title' => 'Не требуется',
                'type' => 'voice',
            ],
            'year' => 1982,
            'kinopoisk_id' => '43949',
            'imdb_id' => 'tt0084716',
            'quality' => 'HDTVRip 720p',
        ];
    }

    public function test_request_uses_correct_endpoint_and_method(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 1,
                'results' => [self::materialPayload()],
            ]),
        ]);

        $connector = new KodikConnector();
        $response = $connector->send(new GetListRequest);

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->resolveEndpoint() === '/list'
                && $request->getMethod()->value === 'POST';
        });

        $this->assertTrue($response->successful());
    }

    public function test_request_returns_dto(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 1,
                'prev_page' => null,
                'next_page' => 'https://kodik-api.com/list?token=TOKEN&next=Y3Vyc29y',
                'results' => [self::materialPayload()],
            ]),
        ]);

        $connector = new KodikConnector();
        $response = $connector->send(new GetListRequest());

        $dto = $response->dtoOrFail();

        $this->assertInstanceOf(KodikListData::class, $dto);
        $this->assertSame('5ms', $dto->time);
        $this->assertSame(1, $dto->total);
        $this->assertCount(1, $dto->results);
        $this->assertSame('movie-452654', $dto->results[0]->id);
        $this->assertSame('Y3Vyc29y', $dto->nextCursor());
    }

    public function test_request_sends_filters_as_query_params(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetListRequest([
            'types' => 'anime-serial',
            'limit' => 100,
        ]));

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial'
                && $request->query()->get('limit') === 100;
        });
    }

    public function test_request_sends_cursor_as_next_query_param(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetListRequest(['types' => 'anime-serial'], 'Y3Vyc29y'));

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('next') === 'Y3Vyc29y'
                && $request->query()->get('types') === 'anime-serial';
        });
    }

    public function test_request_omits_next_query_param_without_cursor(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetListRequest());

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('next') === null;
        });
    }

    public function test_request_omits_null_and_empty_filters(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetListRequest([
            'types' => 'anime-serial',
            'year' => null,
            'sort' => '',
        ]));

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial'
                && $request->query()->get('year') === null
                && $request->query()->get('sort') === null;
        });
    }

    public function test_request_throws_forbidden_on_api_error(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make([
                'error' => 'Invalid token',
            ], 403),
        ]);

        $connector = new KodikConnector();

        $this->expectException(ForbiddenException::class);

        $connector->send(new GetListRequest());
    }
}
