<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Integrations\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeDetailField;
use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Http\Integrations\MyAnimeList\MyAnimeListConnector;
use App\Http\Integrations\MyAnimeList\Requests\GetAnimeDetailsRequest;
use App\Values\MyAnimeList\MyAnimeListAnimeDetails;
use App\Values\MyAnimeList\MyAnimeListAnimeDetailsFilters;
use App\Values\MyAnimeList\MyAnimeListFields;
use App\Values\MyAnimeList\MyAnimeListFieldSelection;
use InvalidArgumentException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

class GetAnimeDetailsRequestTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private static function detailsPayload(): array
    {
        return [
            'id' => 30_230,
            'title' => 'One Piece',
            'main_picture' => [
                'medium' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                'large' => 'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
            ],
            'alternative_titles' => ['en' => 'One Piece'],
        ];
    }

    public function test_request_uses_correct_endpoint_and_method(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(30_230));

        Saloon::assertSent(
            static fn (GetAnimeDetailsRequest $request): bool => $request->resolveEndpoint() === '/v2/anime/30230',
        );

        static::assertTrue($response->successful());
        static::assertSame('GET', $response->getPsrRequest()->getMethod());
    }

    public function test_request_targets_the_anime_path(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(30_230));

        $url = (string) $response->getPsrRequest()->getUri()->withQuery('');

        static::assertSame('https://api.myanimelist.net/v2/anime/30230', $url);
    }

    public function test_request_authenticates_with_the_client_id_header(): void
    {
        config(['noilty.services.myanimelist.client_id' => 'CLIENT-ID']);

        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        $response = (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(30_230));

        static::assertSame('CLIENT-ID', $response->getPsrRequest()->getHeaderLine('X-MAL-CLIENT-ID'));
    }

    public function test_request_returns_the_unwrapped_details_dto(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        $dto = (new MyAnimeListConnector())
            ->send(new GetAnimeDetailsRequest(30_230))
            ->dtoOrFail();

        static::assertInstanceOf(MyAnimeListAnimeDetails::class, $dto);
        static::assertSame(30_230, $dto->anime->id);
        static::assertSame('One Piece', $dto->anime->title);
        static::assertSame('One Piece', $dto->anime->alternativeTitles?->en);
    }

    public function test_request_sends_no_query_when_no_filters_are_set(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(30_230));

        Saloon::assertSent(static fn (GetAnimeDetailsRequest $request): bool => $request->query()->all() === []);
    }

    public function test_request_sends_the_fields_and_nsfw_as_query_params(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        $filters = MyAnimeListAnimeDetailsFilters::make()
            ->withFields(MyAnimeListFields::fromAnimeDetail(
                MyAnimeListAnimeDetailField::STATISTICS,
            )->withSelection(MyAnimeListFieldSelection::make('statistics', 'num_list_users')))
            ->withFields(MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::ID))
            ->withNsfw();

        $response = (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(30_230, $filters->toQuery()));

        parse_str($response->getPsrRequest()->getUri()->getQuery(), $query);

        static::assertSame(['fields' => 'id', 'nsfw' => 'true'], $query);
    }

    public function test_request_omits_null_and_empty_filters(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(self::detailsPayload()),
        ]);

        (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(30_230, [
            'nsfw' => 'true',
            'fields' => '',
        ]));

        Saloon::assertSent(static function (GetAnimeDetailsRequest $request): bool {
            $query = $request->query();

            return $query->get('nsfw') === 'true' && $query->get('fields') === null;
        });
    }

    public function test_request_maps_a_payload_of_only_requested_fields(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(['id' => 1, 'title' => 'Bare']),
        ]);

        $dto = (new MyAnimeListConnector())
            ->send(new GetAnimeDetailsRequest(1))
            ->dtoOrFail();

        static::assertSame(1, $dto->anime->id);
        static::assertNull($dto->anime->mainPicture);
    }

    public function test_request_throws_not_found_for_an_unknown_id(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(['error' => 'Not found'], 404),
        ]);

        $this->expectException(NotFoundException::class);

        (new MyAnimeListConnector())->send(new GetAnimeDetailsRequest(999_999));
    }

    public function test_request_rejects_a_payload_without_an_id(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make(['title' => 'One Piece']),
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: id');

        (new MyAnimeListConnector())
            ->send(new GetAnimeDetailsRequest(30_230))
            ->dtoOrFail();
    }

    public function test_request_does_not_map_a_body_that_is_not_json(): void
    {
        Saloon::fake([
            GetAnimeDetailsRequest::class => MockResponse::make('<html>maintenance</html>', 200),
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('API returned a body that is not valid JSON.');

        (new MyAnimeListConnector())
            ->send(new GetAnimeDetailsRequest(30_230))
            ->dtoOrFail();
    }
}
