# MyAnimeList API: `GET /anime/{anime_id}`

> Получение детальной информации об аниме по ID.

## Назначение

Получение полной информации об аниме, включая картинки, связанные работы, рекомендации, статистику и статус пользователя.

## Endpoint

`GET https://api.myanimelist.net/v2/anime/{anime_id}`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| anime_id | Да | integer | ID аниме. |

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| fields | — | string | Поля для возврата. Формат: `field1,field2,nested{sub1,sub2}`. См. список полей ниже. |
| nsfw | false | true, false | Включать NSFW-контент. |

## Доступные поля (fields)

Все поля из [`anime-list.md`](anime-list.md#доступные-поля-fields) плюс дополнительные:

- `pictures` — array of objects `{large?, medium}` (не возвращается в списке)
- `background` — string или null (не возвращается в списке)
- `related_anime` — array of objects `{node, relation_type, relation_type_formatted}` (не возвращается в списке)
- `related_manga` — array of objects `{node, relation_type, relation_type_formatted}` (не возвращается в списке)
- `recommendations` — array of objects `{node, num_recommendations}` (не возвращается в списке)
- `statistics` — object `{num_list_users, status: {watching, completed, on_hold, dropped, plan_to_watch}}` (не возвращается в списке)

### Детали вложенных объектов

**pictures**: массив объектов с `large` (string или null, URI) и `medium` (string, URI).

**related_anime / related_manga**: массив объектов:
- `node` — объект аниме/манги (рекурсивная схема, базовые поля)
- `relation_type` — string (`sequel`, `prequel`, `alternative_setting`, `alternative_version`, `side_story`, `parent_story`, `summary`, `full_story`)
- `relation_type_formatted` — string (человекочитаемый формат, напр. "Alternative version")

**recommendations**: массив объектов:
- `node` — объект аниме (рекурсивная схема)
- `num_recommendations` — integer

**statistics**: объект:
- `num_list_users` — integer
- `status` — объект с полями `watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch` (все integer)

## Структура ответа

Возвращается один объект аниме (не обёрнут в `data` массив):

```json
{
  "id": 30230,
  "title": "One Piece",
  "main_picture": {...},
  "alternative_titles": {...},
  "start_date": "1999-10-20",
  "end_date": null,
  "synopsis": "...",
  "mean": 8.73,
  "rank": 10,
  "popularity": 1,
  "num_list_users": 2500000,
  "num_scoring_users": 2000000,
  "nsfw": "white",
  "genres": [...],
  "created_at": "2006-03-15T00:00:00+00:00",
  "updated_at": "2024-01-15T00:00:00+00:00",
  "media_type": "tv",
  "status": "currently_airing",
  "my_list_status": {...},
  "num_episodes": 1100,
  "start_season": {"year": 1999, "season": "fall"},
  "broadcast": {"day_of_the_week": "sunday", "start_time": "09:30"},
  "source": "manga",
  "average_episode_duration": 1440,
  "rating": "pg_13",
  "studios": [...],
  "pictures": [...],
  "background": "...",
  "related_anime": [...],
  "related_manga": [...],
  "recommendations": [...],
  "statistics": {...}
}
```

## Пример запроса

```http
GET https://api.myanimelist.net/v2/anime/30230?fields=id,title,main_picture,alternative_titles,start_date,end_date,synopsis,mean,rank,popularity,num_list_users,num_scoring_users,nsfw,created_at,updated_at,media_type,status,genres,my_list_status,num_episodes,start_season,broadcast,source,average_episode_duration,rating,pictures,background,related_anime,related_manga,recommendations,studios,statistics
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "id": 30230,
  "title": "One Piece",
  "main_picture": {
    "medium": "https://cdn.myanimelist.net/images/anime/6/73245.jpg",
    "large": "https://cdn.myanimelist.net/images/anime/6/73245l.jpg"
  },
  "alternative_titles": {
    "synonyms": ["One Piece", "ワンピース"],
    "en": "One Piece",
    "ja": "ワンピース"
  },
  "start_date": "1999-10-20",
  "end_date": null,
  "synopsis": "Гол Д. Роджер был известен как «Король пиратов»...",
  "mean": 8.73,
  "rank": 10,
  "popularity": 1,
  "num_list_users": 2500000,
  "num_scoring_users": 2000000,
  "nsfw": "white",
  "genres": [
    {"id": 2, "name": "Adventure"},
    {"id": 25, "name": "Comedy"},
    {"id": 41, "name": "Action"}
  ],
  "created_at": "2006-03-15T00:00:00+00:00",
  "updated_at": "2024-01-15T00:00:00+00:00",
  "media_type": "tv",
  "status": "currently_airing",
  "my_list_status": {
    "status": "watching",
    "score": 9,
    "num_episodes_watched": 500,
    "is_rewatching": false,
    "start_date": "2020-01-15",
    "finish_date": null,
    "priority": 1,
    "num_times_rewatched": 0,
    "rewatch_value": 0,
    "tags": ["favorite", "long-running"],
    "comments": "Best anime ever",
    "updated_at": "2024-01-10T12:00:00+00:00"
  },
  "num_episodes": 1100,
  "start_season": {"year": 1999, "season": "fall"},
  "broadcast": {"day_of_the_week": "sunday", "start_time": "09:30"},
  "source": "manga",
  "average_episode_duration": 1440,
  "rating": "pg_13",
  "studios": [
    {"id": 1, "name": "Toei Animation"}
  ],
  "pictures": [
    {"large": "https://cdn.myanimelist.net/images/anime/6/73245l.jpg", "medium": "https://cdn.myanimelist.net/images/anime/6/73245.jpg"},
    {"large": "https://cdn.myanimelist.net/images/anime/6/73246l.jpg", "medium": "https://cdn.myanimelist.net/images/anime/6/73246.jpg"}
  ],
  "background": "One Piece — одно из самых долгоиграющих аниме...",
  "related_anime": [
    {
      "node": {"id": 32259, "title": "One Piece Film: Gold", "main_picture": {...}},
      "relation_type": "side_story",
      "relation_type_formatted": "Side story"
    }
  ],
  "related_manga": [
    {
      "node": {"id": 13, "title": "One Piece", "main_picture": {...}},
      "relation_type": "manga",
      "relation_type_formatted": "Manga"
    }
  ],
  "recommendations": [
    {
      "node": {"id": 21, "title": "Naruto", "main_picture": {...}},
      "num_recommendations": 50000
    }
  ],
  "statistics": {
    "num_list_users": 2500000,
    "status": {
      "watching": 500000,
      "completed": 800000,
      "on_hold": 200000,
      "dropped": 100000,
      "plan_to_watch": 900000
    }
  }
}
```

> Ответ сокращён: массивы укорочены, вложенные объекты показаны частично.

## Правила для ИИ-агента

- Не добавлять параметры и поля ответа, отсутствующие в этом документе.
- Поля `pictures`, `background`, `related_anime`, `related_manga`, `recommendations`, `statistics` не возвращаются в списке — запрашивай их явно через `fields`.
- `my_list_status` возвращается только при OAuth2 авторизации.
- `relation_type_formatted` предназначен для отображения, не используй для логики.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).