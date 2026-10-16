<?php

declare(strict_types=1);

namespace Tests\Unit\Values\MyAnimeList;

use App\Enums\MyAnimeList\MyAnimeListAnimeDetailField;
use App\Enums\MyAnimeList\MyAnimeListAnimeNodeField;
use App\Enums\MyAnimeList\MyAnimeListMangaNodeField;
use App\Enums\MyAnimeList\MyAnimeListMediaKind;
use App\Values\MyAnimeList\MyAnimeListFields;
use App\Values\MyAnimeList\MyAnimeListFieldSelection;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MyAnimeListFieldsTest extends TestCase
{
    public function test_anime_node_fields_serialize_comma_separated(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(
            MyAnimeListAnimeNodeField::ID,
            MyAnimeListAnimeNodeField::TITLE,
            MyAnimeListAnimeNodeField::MEAN,
        );

        static::assertSame('id,title,mean', $fields->toQueryValue());
    }

    public function test_nested_sub_fields_serialize_with_braces(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(
            MyAnimeListAnimeNodeField::MY_LIST_STATUS,
        )->withSelection(MyAnimeListFieldSelection::make('my_list_status', 'status', 'score'));

        static::assertSame('my_list_status{status,score}', $fields->toQueryValue());
    }

    public function test_sub_fields_nest_to_the_depth_the_api_nests_them(): void
    {
        $fields = MyAnimeListFields::fromAnimeDetail(
            MyAnimeListAnimeDetailField::STATISTICS,
        )->withSelection(MyAnimeListFieldSelection::make(
            'statistics',
            'num_list_users',
            MyAnimeListFieldSelection::make('status', 'watching', 'completed'),
        ));

        static::assertSame('statistics{num_list_users,status{watching,completed}}', $fields->toQueryValue());
    }

    public function test_a_selection_replaces_the_plain_name_it_narrows(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(
            MyAnimeListAnimeNodeField::ID,
            MyAnimeListAnimeNodeField::MY_LIST_STATUS,
        )->withSelection(MyAnimeListFieldSelection::make('my_list_status', 'status'));

        static::assertSame('id,my_list_status{status}', $fields->toQueryValue());
    }

    public function test_a_selection_is_rejected_when_the_endpoint_does_not_request_the_field(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::ID);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field is not requested by this endpoint: my_list_status');

        $fields->withSelection(MyAnimeListFieldSelection::make('my_list_status', 'status'));
    }

    public function test_undocumented_sub_fields_are_rejected(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::MY_LIST_STATUS);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undocumented sub-field of my_list_status: num_times_rewatching_it');

        $fields->withSelection(MyAnimeListFieldSelection::make('my_list_status', 'num_times_rewatching_it'));
    }

    public function test_sub_fields_of_a_field_that_documents_none_are_rejected(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::GENRES);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field has no documented sub-fields: genres');

        $fields->withSelection(MyAnimeListFieldSelection::make('genres', 'id'));
    }

    public function test_undocumented_grandchildren_are_rejected(): void
    {
        $fields = MyAnimeListFields::fromMangaNode(MyAnimeListMangaNodeField::AUTHORS);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undocumented sub-field of node: native_name');

        $fields->withSelection(MyAnimeListFieldSelection::make('authors', MyAnimeListFieldSelection::make(
            'node',
            'first_name',
            'native_name',
        )));
    }

    public function test_manga_and_anime_list_status_sub_fields_do_not_mix(): void
    {
        $fields = MyAnimeListFields::fromMangaNode(MyAnimeListMangaNodeField::MY_LIST_STATUS);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undocumented sub-field of my_list_status: num_episodes_watched');

        $fields->withSelection(MyAnimeListFieldSelection::make('my_list_status', 'num_episodes_watched'));
    }

    public function test_manga_authors_nest_to_three_levels(): void
    {
        $fields = MyAnimeListFields::fromMangaNode(
            MyAnimeListMangaNodeField::AUTHORS,
        )->withSelection(MyAnimeListFieldSelection::make(
            'authors',
            MyAnimeListFieldSelection::make('node', 'id', 'first_name', 'last_name'),
            'role',
        ));

        static::assertSame('authors{node{id,first_name,last_name},role}', $fields->toQueryValue());
    }

    public function test_a_field_name_must_not_be_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field name must not be empty.');

        MyAnimeListFieldSelection::make('');
    }

    public function test_to_array_returns_the_media_kind_and_selections(): void
    {
        $fields = MyAnimeListFields::fromAnimeNode(MyAnimeListAnimeNodeField::ID);

        static::assertSame(
            [
                'media_kind' => MyAnimeListMediaKind::ANIME,
                'selections' => [['name' => 'id', 'children' => []]],
            ],
            $fields->toArray(),
        );
    }
}
