<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\KodikAnimeKind;
use App\Enums\KodikFilter;
use App\Enums\KodikListFilter;
use App\Enums\KodikMaterialField;
use App\Enums\KodikMaterialType;
use App\Enums\KodikRatingMpaa;
use App\Enums\KodikStatus;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetListRequest;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Services\KodikService;
use App\Values\Kodik\KodikFilters;
use App\Values\Kodik\KodikListData;
use App\Values\Kodik\KodikTranslationsData;
use InvalidArgumentException;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class KodikServiceTest extends TestCase
{
    /**
     * @param array<string, mixed> $body
     */
    private static function fakeTranslations(array $body = ['time' => '5ms', 'total' => 0, 'results' => []]): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make($body),
        ]);
    }

    /**
     * @param array<string, mixed> $body
     */
    private static function fakeList(array $body = ['time' => '5ms', 'total' => 0, 'results' => []]): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make($body),
        ]);
    }

    public function test_get_translations_returns_dto(): void
    {
        self::fakeTranslations([
            'time' => '5ms',
            'total' => 2,
            'results' => [
                ['id' => 735, 'title' => '2x2', 'count' => 26],
                ['id' => 824, 'title' => '3df voice', 'count' => 16],
            ],
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
        self::fakeTranslations();

        $service = new KodikService(new KodikConnector());
        $service->getTranslations(
            KodikFilters::forTranslations()
                ->withTypes(KodikMaterialType::ANIME_SERIAL)
                ->with(KodikFilter::SORT, 'title'),
        );

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial'
                && $request->query()->get('sort') === 'title';
        });
    }

    public function test_get_translations_with_empty_filters_sends_no_query_params(): void
    {
        self::fakeTranslations();

        $service = new KodikService(new KodikConnector());
        $service->getTranslations();

        Saloon::assertSent(function (GetTranslationsRequest $request): bool {
            return $request->query()->isEmpty();
        });
    }

    public function test_get_translations_rejects_list_only_filter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter key: limit');

        self::fakeTranslations();

        $service = new KodikService(new KodikConnector());
        $service->getTranslations(
            KodikFilters::forTranslations()->with(KodikListFilter::LIMIT, 100),
        );
    }

    public function test_get_translations_propagates_api_errors(): void
    {
        Saloon::fake([
            GetTranslationsRequest::class => MockResponse::make(['error' => 'Invalid token'], 403),
        ]);

        $service = new KodikService(new KodikConnector());

        $this->expectException(ForbiddenException::class);

        $service->getTranslations();
    }

    public function test_get_list_returns_dto(): void
    {
        self::fakeList([
            'time' => '5ms',
            'total' => 1,
            'results' => [
                [
                    'id' => 'serial-452654',
                    'type' => 'anime-serial',
                    'title' => 'Игра престолов',
                    'year' => 2011,
                ],
            ],
        ]);

        $service = new KodikService(new KodikConnector());
        $result = $service->getList();

        $this->assertInstanceOf(KodikListData::class, $result);
        $this->assertSame('5ms', $result->time);
        $this->assertSame(1, $result->total);
        $this->assertCount(1, $result->results);
        $this->assertSame('serial-452654', $result->results[0]->id);
    }

    public function test_get_list_sends_every_named_filter_as_query_params(): void
    {
        self::fakeList();

        $service = new KodikService(new KodikConnector());
        $service->getList(
            KodikFilters::forList()
                ->withLimit(100)
                ->withTypes(KodikMaterialType::ANIME, KodikMaterialType::ANIME_SERIAL)
                ->withField(KodikMaterialField::SHIKIMORI_ID)
                ->withAnimeKind(KodikAnimeKind::TV, KodikAnimeKind::TV_24)
                ->withAnimeStatus(KodikStatus::RELEASED)
                ->withDramaStatus(KodikStatus::ONGOING)
                ->withAnyStatus(KodikStatus::ANONS)
                ->withRatingMpaa(KodikRatingMpaa::PG_13, KodikRatingMpaa::R_PLUS),
        );

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('limit') === 100
                && $request->query()->get('types') === 'anime,anime-serial'
                && $request->query()->get('has_field') === 'shikimori_id'
                && $request->query()->get('anime_kind') === 'tv,tv_24'
                && $request->query()->get('anime_status') === 'released'
                && $request->query()->get('drama_status') === 'ongoing'
                && $request->query()->get('all_status') === 'anons'
                && $request->query()->get('rating_mpaa') === 'PG-13,R+';
        });
    }

    public function test_get_list_accepts_shared_and_list_only_filters(): void
    {
        self::fakeList();

        $service = new KodikService(new KodikConnector());
        $service->getList(
            KodikFilters::forList()
                ->with(KodikFilter::TYPES, 'anime-serial')
                ->with(KodikFilter::SORT, 'updated_at')
                ->with(KodikListFilter::LIMIT, 100)
                ->with(KodikListFilter::ORDER, 'asc'),
        );

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('types') === 'anime-serial'
                && $request->query()->get('sort') === 'updated_at'
                && $request->query()->get('limit') === 100
                && $request->query()->get('order') === 'asc';
        });
    }

    public function test_get_list_forwards_cursor(): void
    {
        self::fakeList();

        $service = new KodikService(new KodikConnector());
        $service->getList(
            KodikFilters::forList()->withTypes(KodikMaterialType::ANIME_SERIAL),
            'Y3Vyc29y',
        );

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->get('next') === 'Y3Vyc29y'
                && $request->query()->get('types') === 'anime-serial';
        });
    }

    public function test_get_list_with_empty_filters_sends_no_query_params(): void
    {
        self::fakeList();

        $service = new KodikService(new KodikConnector());
        $service->getList();

        Saloon::assertSent(function (GetListRequest $request): bool {
            return $request->query()->isEmpty();
        });
    }

    public function test_get_list_propagates_api_errors(): void
    {
        Saloon::fake([
            GetListRequest::class => MockResponse::make(['error' => 'Invalid token'], 403),
        ]);

        $service = new KodikService(new KodikConnector());

        $this->expectException(ForbiddenException::class);

        $service->getList();
    }
}
