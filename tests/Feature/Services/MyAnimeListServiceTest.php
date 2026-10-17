<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Enums\MyAnimeList\MyAnimeListRankingTypeAnimeFilter;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeDetailsRequest;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeRankingRequest;
use App\Services\MyAnimeListService;
use App\Values\MyAnimeList\MyAnimeListAnimeDetailsFilters;
use App\Values\MyAnimeList\MyAnimeListAnimeRankingFilters;
use App\Values\MyAnimeList\MyAnimeListFields;
use InvalidArgumentException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class MyAnimeListServiceTest extends TestCase
{
    public function test_it_sends_the_ranking_request_and_returns_the_data(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make([
                'data' => [
                    [
                        'node' => ['id' => 30_230, 'title' => 'One Piece', 'main_picture' => null],
                        'ranking' => ['rank' => 1],
                    ],
                ],
                'paging' => ['previous' => null, 'next' => null],
            ]),
        ]);

        $data = $this->service()->getAnimeRanking(MyAnimeListRankingTypeAnimeFilter::ALL);

        Saloon::assertSent(
            static fn (GetAnimeRankingRequest $request): bool => $request->query()->get('ranking_type') === 'all',
        );

        static::assertCount(1, $data->data);
        static::assertSame(30_230, $data->data[0]->node->id);
        static::assertFalse($data->paging->hasNextPage());
    }

    public function test_it_applies_the_filters_it_is_given(): void
    {
        Saloon::fake([
            GetAnimeRankingRequest::class => MockResponse::make(['data' => [], 'paging' => ['next' => null]]),
        ]);

        $filters = MyAnimeListAnimeRankingFilters::make()
            ->withLimit(500)
            ->withOffset(500)
            ->withFields(MyAnimeListFields::fromAnimeNode(
                MyAnimeListAnimeNodeField::ID,
                MyAnimeListAnimeNodeField::MEAN,
            ));

        $this->service()->getAnimeRanking(MyAnimeListRankingTypeAnimeFilter::AIRING, $filters);

        Saloon::assertSent(static function (GetAnimeRankingRequest $request): bool {
            $query = $request->query();

            return (
                $query->get('ranking_type') === 'airing'
                && $query->get('limit') === 500
                && $query->get('offset') === 500
                && $query->get('fields') === 'id,mean'
            );
        });
    }

    public function test_it_sends_the_details_request_and_returns_the_anime(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make([
                'id' => 30_230,
                'title' => 'One Piece',
                'alternative_titles' => ['en' => 'One Piece'],
            ]),
        ]);

        $details = $this->service()->getAnimeDetails(30_230);

        Saloon::assertSent(
            static fn (GetAnimeDetailsRequest $request): bool => $request->resolveEndpoint() === '/v2/anime/30230',
        );

        static::assertSame(30_230, $details->anime->id);
        static::assertSame('One Piece', $details->anime->alternativeTitles?->en);
    }

    public function test_it_applies_the_details_filters_it_is_given(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(['id' => 1, 'title' => 'Bare']),
        ]);

        $filters = MyAnimeListAnimeDetailsFilters::make()
            ->withFields(MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::ID))
            ->withNsfw();

        $this->service()->getAnimeDetails(1, $filters);

        Saloon::assertSent(static function (GetAnimeDetailsRequest $request): bool {
            $query = $request->query();

            return $query->get('fields') === 'id' && $query->get('nsfw') === 'true';
        });
    }

    public function test_it_rejects_a_non_positive_anime_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Anime id must be a positive integer, got 0.');

        $this->service()->getAnimeDetails(0);
    }

    public function test_it_resolves_the_connector_from_the_container(): void
    {
        static::assertInstanceOf(MyAnimeListService::class, $this->service());
    }

    private function service(): MyAnimeListService
    {
        return $this->app->make(MyAnimeListService::class);
    }
}
