<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Values\MyAnimeList\MyAnimeListAnimeDetails;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListAnimeDetailsTest extends TestCase
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
            'alternative_titles' => ['synonyms' => ['One Piece'], 'en' => 'One Piece', 'ja' => 'ワンピース'],
        ];
    }

    public function test_from_array_returns_the_node_unwrapped(): void
    {
        $details = MyAnimeListAnimeDetails::fromArray(self::detailsPayload());

        static::assertSame(30_230, $details->anime->id);
        static::assertSame('One Piece', $details->anime->title);
        static::assertSame(
            'https://cdn.myanimelist.net/images/anime/6/73245l.jpg',
            $details->anime->mainPicture?->large,
        );
        static::assertSame('One Piece', $details->anime->alternativeTitles?->en);
    }

    public function test_from_array_maps_a_payload_of_only_requested_fields(): void
    {
        $details = MyAnimeListAnimeDetails::fromArray(['id' => 1, 'title' => 'Bare']);

        static::assertSame(1, $details->anime->id);
        static::assertNull($details->anime->mainPicture);
        static::assertNull($details->anime->alternativeTitles);
    }

    public function test_from_array_drops_the_fields_the_details_endpoints_add(): void
    {
        $details = MyAnimeListAnimeDetails::fromArray(
            self::detailsPayload()
            + [
                'background' => 'Longest running anime.',
                'statistics' => ['num_list_users' => 2_500_000],
            ],
        );

        static::assertSame(['id', 'title', 'main_picture', 'alternative_titles'], array_keys($details->toArray()));
    }

    public function test_from_array_rejects_a_payload_without_an_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required field is missing: id');

        MyAnimeListAnimeDetails::fromArray(['title' => 'One Piece']);
    }

    public function test_to_array_returns_the_api_shape(): void
    {
        static::assertSame(
            self::detailsPayload(),
            MyAnimeListAnimeDetails::fromArray(self::detailsPayload())->toArray(),
        );
    }

    public function test_it_round_trips_through_to_array(): void
    {
        $details = MyAnimeListAnimeDetails::fromArray(self::detailsPayload());

        static::assertSame($details->toArray(), MyAnimeListAnimeDetails::fromArray($details->toArray())->toArray());
    }
}
