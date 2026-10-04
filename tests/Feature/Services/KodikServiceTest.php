<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\KodikFilter;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Services\KodikService;
use App\Values\Kodik\KodikTranslationsData;
use InvalidArgumentException;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class KodikServiceTest extends TestCase
{
    public function test_get_translations_returns_dto(): void
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

        $service = new KodikService(new KodikConnector());
        $result = $service->getTranslations();

        $this->assertInstanceOf(KodikTranslationsData::class, $result);
        $this->assertSame('5ms', $result->time);
        $this->assertSame(2, $result->total);
        $this->assertCount(2, $result->results);
    }

    public function test_get_translations_passes_filters_to_request(): void
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

        $service = new KodikService(new KodikConnector());
        $service->getTranslations([
            KodikFilter::TYPES->value => 'anime-serial',
            KodikFilter::SORT->value => 'title',
        ]);

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial'
                && $request->query()->get('sort') === 'title';
        });
    }

    public function test_get_translations_throws_on_invalid_filter_key(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: invalid_filter');

        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $service = new KodikService(new KodikConnector());
        $service->getTranslations(['invalid_filter' => 'value']);
    }

    public function test_get_translations_throws_on_multiple_invalid_filter_keys(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: bad_key');

        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $service = new KodikService(new KodikConnector());
        $service->getTranslations([
            KodikFilter::TYPES->value => 'anime-serial',
            'bad_key' => 'value',
        ]);
    }

    public function test_get_translations_propagates_api_errors(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'error' => 'Invalid token',
            ], 403),
        ]);

        $service = new KodikService(new KodikConnector());

        $this->expectException(ForbiddenException::class);

        $service->getTranslations();
    }

    public function test_get_translations_with_empty_filters_sends_no_query_params(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make([
                'time' => '5ms',
                'total' => 0,
                'results' => [],
            ]),
        ]);

        $service = new KodikService(new KodikConnector());
        $service->getTranslations();

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->query()->isEmpty();
        });
    }
}
