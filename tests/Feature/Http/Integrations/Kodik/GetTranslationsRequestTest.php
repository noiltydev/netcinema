<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Integrations\Kodik;

use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Values\Kodik\KodikTranslationsData;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class GetTranslationsRequestTest extends TestCase
{
    public function test_request_uses_correct_endpoint_and_method(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 2,
                'results' => [
                    ['id' => 735, 'title' => '2x2', 'count' => 26],
                    ['id' => 824, 'title' => '3df voice', 'count' => 16],
                ],
            ]),
        ]);

        $connector = new KodikConnector();
        $response = $connector->send(new GetTranslationsRequest());

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->resolveEndpoint() === '/translations/v2'
                && $request->getMethod()->value === 'POST';
        });

        $this->assertTrue($response->successful());
    }

    public function test_request_returns_dto(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 2,
                'results' => [
                    ['id' => 735, 'title' => '2x2', 'count' => 26],
                    ['id' => 824, 'title' => '3df voice', 'count' => 16],
                ],
            ]),
        ]);

        $connector = new KodikConnector();
        $response = $connector->send(new GetTranslationsRequest());

        $dto = $response->dtoOrFail();

        $this->assertInstanceOf(KodikTranslationsData::class, $dto);
        $this->assertSame('5ms', $dto->time);
        $this->assertSame(2, $dto->total);
        $this->assertCount(2, $dto->results);
    }

    public function test_request_sends_filters_as_query_params(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 1,
                'results' => [
                    ['id' => 735, 'title' => '2x2', 'count' => 26],
                ],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetTranslationsRequest(['types' => 'anime-serial']));

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial';
        });
    }

    public function test_request_omits_null_and_empty_filters(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $connector = new KodikConnector();
        $connector->send(new GetTranslationsRequest([
            'types' => 'anime-serial',
            'year' => null,
            'sort' => '',
        ]));

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial'
                && $request->query()->get('year') === null
                && $request->query()->get('sort') === null;
        });
    }

    public function test_request_throws_forbidden_on_api_error(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'error' => 'Invalid token',
            ], 403),
        ]);

        $connector = new KodikConnector();

        $this->expectException(ForbiddenException::class);

        $connector->send(new GetTranslationsRequest());
    }
}
