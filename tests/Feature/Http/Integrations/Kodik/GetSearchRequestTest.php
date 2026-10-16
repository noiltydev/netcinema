<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Integrations\Kodik;

use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetSearchRequest;
use App\Values\Kodik\KodikSearchData;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class GetSearchRequestTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private static function materialPayload(): array
    {
        return [
            'id' => 'movie-452654',
            'type' => 'foreign-movie',
            'title' => 'Спортлото-82',
            'title_orig' => 'Спортлото-82',
            'year' => 1982,
        ];
    }

    public function test_request_uses_correct_endpoint_and_method(): void
    {
        Saloon::fake([
            GetSearchRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 1,
                'results' => [self::materialPayload()],
            ]),
        ]);

        $connector = new KodikConnector();
        $response = $connector->send(new GetSearchRequest());

        Saloon::assertSent(static function (GetSearchRequest $request): bool {
            return $request->resolveEndpoint() === '/search' && $request->getMethod()->value === 'GET';
        });

        static::assertTrue($response->successful());
    }

    public function test_request_returns_dto(): void
    {
        Saloon::fake([
            GetSearchRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 1,
                'results' => [self::materialPayload()],
            ]),
        ]);

        $connector = new KodikConnector();
        $response = $connector->send(new GetSearchRequest());

        $dto = $response->dtoOrFail();

        static::assertInstanceOf(KodikSearchData::class, $dto);
        static::assertSame('5ms', $dto->time);
        static::assertSame(1, $dto->total);
        static::assertCount(1, $dto->results);
        static::assertSame('movie-452654', $dto->results[0]->id);
    }

    public function test_request_sends_filters_as_query_params(): void
    {
        Saloon::fake([
            GetSearchRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetSearchRequest([
            'title' => 'Спортлото',
            'limit' => 100,
        ]));

        Saloon::assertSent(static function (GetSearchRequest $request): bool {
            return $request->query()->get('title') === 'Спортлото' && $request->query()->get('limit') === 100;
        });
    }

    public function test_request_sends_no_cursor_query_param(): void
    {
        Saloon::fake([
            GetSearchRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetSearchRequest(['title' => 'Спортлото']));

        Saloon::assertSent(static function (GetSearchRequest $request): bool {
            return $request->query()->get('next') === null;
        });
    }

    public function test_request_omits_null_and_empty_filters(): void
    {
        Saloon::fake([
            GetSearchRequest::class => MockResponse::make([
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetSearchRequest([
            'title' => 'Спортлото',
            'title_orig' => null,
            'sort' => '',
        ]));

        Saloon::assertSent(static function (GetSearchRequest $request): bool {
            return (
                $request->query()->get('title') === 'Спортлото'
                && $request->query()->get('title_orig') === null
                && $request->query()->get('sort') === null
            );
        });
    }

    public function test_request_throws_forbidden_on_api_error(): void
    {
        Saloon::fake([
            GetSearchRequest::class => MockResponse::make([
                'error' => 'Invalid token',
            ], 403),
        ]);

        $connector = new KodikConnector();

        $this->expectException(ForbiddenException::class);

        $connector->send(new GetSearchRequest(['title' => 'Спортлото']));
    }
}
