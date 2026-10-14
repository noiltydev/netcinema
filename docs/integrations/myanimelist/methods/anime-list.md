# MyAnimeList API: `GET /anime`

> Получение списка аниме с возможностью поиска и пагинации.

## Назначение

Получение списка аниме с фильтрацией по поисковому запросу, пагинацией и выбором полей.

## Endpoint

`GET https://api.myanimelist.net/v2/anime`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры запроса

### Необязательные параметры

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| q | — | string | Поисковый запрос. |
| limit | 100 | integer (1–100) | Максимальное количество результатов. Максимум 100. |
| offset | 0 | integer | Смещение для пагинации. |
| fields | — | string | Поля для возврата. Формат: `field1,field2,nested{sub1,sub2}`. См. [`../parameters/common.md`](../parameters/common.md#fields). |
| nsfw | false | true, false | Включать NSFW-контент. |

### Доступные поля (fields)

Основные поля узла (node):
- `id` — integer
- `title` — string
- `main_picture` — object `{large?, medium}` или null
- `alternative_titles` — object `{synonyms?, en?, ja?}` или null
- `start_date` — string (date) или null
- `end_date` — string (date) или null
- `synopsis` — string или null
- `mean` — number (float) или null
- `rank` — integer или null
- `popularity` — integer или null
- `num_list_users` — integer
- `num_scoring_users` — integer
- `nsfw` — string (`white`, `gray`, `black`) или null
- `genres` — array of objects `{id, name}`
- `created_at` — string (date-time)
- `updated_at` — string (date-time)
- `media_type` — string (`unknown`, `tv`, `ova`, `movie`, `special`, `ona`, `music`)
- `status` — string (`finished_airing`, `currently_airing`, `not_yet_aired`)
- `my_list_status` — object (см. ниже) или null (требует OAuth2)
- `num_episodes` — integer
- `start_season` — object `{year, season}` или null
- `broadcast` — object `{day_of_the_week, start_time}` или null
- `source` — string (enum: `other`, `original`, `manga`, `4_koma_manga`, `web_manga`, `digital_manga`, `novel`, `light_novel`, `visual_novel`, `game`, `card_game`, `book`, `picture_book`, `radio`, `music`)
- `average_episode_duration` — integer или null
- `rating` — string (`g`, `pg`, `pg_13`, `r`, `r+`, `rx`) или null
- `studios` — array of objects `{id, name}`

> **Значения выходят за рамки документации**: перечисленные выше enum'ы ниже не ограничены схемой —
> API возвращает для них обычные строки без валидации. В реальных ответах встречались
> `media_type: tv_special`, `source: web_novel` и `source: mixed_media`, которых нет ни в этом
> списке, ни в спецификации MAL. Приводи значения к доменным enum через безопасный `tryFrom()`
> с fallback в null, а не через строгий `match` с исключением.

Поля `my_list_status` (требуют OAuth2, указываются как `my_list_status{status,score,...}`):
- `status` — string (`watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch`)
- `score` — integer (0–10)
- `num_episodes_watched` — integer
- `is_rewatching` — boolean
- `start_date` — string (date) или null
- `finish_date` — string (date) или null
- `priority` — integer
- `num_times_rewatched` — integer
- `rewatch_value` — integer
- `tags` — array of strings
- `comments` — string
- `updated_at` — string (date-time)

## Структура ответа

```json
{
  "data": [
    {
      "node": { ... }
    }
  ],
  "paging": {
    "previous": "https://...",
    "next": "https://..."
  }
}
```

- `data` — массив объектов с полем `node` (схема аниме выше).
- `paging` — объект с ссылками `previous` и `next` для пагинации (могут быть null).

## Пример запроса

```http
GET https://api.myanimelist.net/v2/anime?q=one&limit=4
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

или

```http
GET https://api.myanimelist.net/v2/anime?q=one&limit=4
X-MAL-CLIENT-ID: <MAL_CLIENT_ID>
```

## Пример ответа

```json
{
  "data": [
    {
      "node": {
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
          {"id": 25, "name": "Comedy"}
        ],
        "created_at": "2006-03-15T00:00:00+00:00",
        "updated_at": "2024-01-15T00:00:00+00:00",
        "media_type": "tv",
        "status": "currently_airing",
        "num_episodes": 1100,
        "start_season": {"year": 1999, "season": "fall"},
        "broadcast": {"day_of_the_week": "sunday", "start_time": "09:30"},
        "source": "manga",
        "average_episode_duration": 1440,
        "rating": "pg_13",
        "studios": [
          {"id": 1, "name": "Toei Animation"}
        ]
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/anime?q=one&limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект, массивы укорочены.

## Правила для ИИ-агента

- Не добавлять параметры и поля ответа, отсутствующие в этом документе.
- Для пагинации используй ссылки `paging.next` / `paging.previous`, возвращённые API.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).
- Поле `my_list_status` возвращается только при OAuth2 авторизации.