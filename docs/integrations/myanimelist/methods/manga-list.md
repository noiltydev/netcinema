# MyAnimeList API: `GET /manga`

> Получение списка манги с возможностью поиска и пагинации.

## Назначение

Получение списка манги с фильтрацией по поисковому запросу, пагинацией и выбором полей.

## Endpoint

`GET https://api.myanimelist.net/v2/manga`

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
| fields | — | string | Поля для возврата. Формат: `field1,field2,nested{sub1,sub2}`. См. список полей ниже. |
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
- `media_type` — string (`unknown`, `manga`, `novel`, `one_shot`, `doujinshi`, `manhwa`, `manhua`, `oel`)
- `status` — string (`finished`, `currently_publishing`, `not_yet_published`)
- `my_list_status` — object (см. ниже) или null (требует OAuth2)
- `num_volumes` — integer (если неизвестно — 0)
- `num_chapters` — integer (если неизвестно — 0)
- `authors` — array of objects `{node: {id, first_name, last_name}, role}`

Поля `my_list_status` (требуют OAuth2, указываются как `my_list_status{status,score,...}`):
- `status` — string (`reading`, `completed`, `on_hold`, `dropped`, `plan_to_read`)
- `score` — integer (0–10)
- `num_volumes_read` — integer
- `num_chapters_read` — integer
- `is_rereading` — boolean
- `start_date` — string (date) или null
- `finish_date` — string (date) или null
- `priority` — integer
- `num_times_reread` — integer
- `reread_value` — integer
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

- `data` — массив объектов с полем `node` (схема манги выше).
- `paging` — объект с ссылками `previous` и `next` для пагинации (могут быть null).

## Пример запроса

```http
GET https://api.myanimelist.net/v2/manga?q=berserk
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

или

```http
GET https://api.myanimelist.net/v2/manga?q=berserk
X-MAL-CLIENT-ID: <MAL_CLIENT_ID>
```

## Пример ответа

```json
{
  "data": [
    {
      "node": {
        "id": 2,
        "title": "Berserk",
        "main_picture": {
          "medium": "https://cdn.myanimelist.net/images/manga/2/173994.jpg",
          "large": "https://cdn.myanimelist.net/images/manga/2/173994l.jpg"
        },
        "alternative_titles": {
          "synonyms": ["Berserk", "ベルセルク"],
          "en": "Berserk",
          "ja": "ベルセルク"
        },
        "start_date": "1989-08-25",
        "end_date": null,
        "synopsis": "Гаттс — наёмник, который сражается с демонами...",
        "mean": 9.2,
        "rank": 2,
        "popularity": 5,
        "num_list_users": 500000,
        "num_scoring_users": 450000,
        "nsfw": "black",
        "genres": [
          {"id": 1, "name": "Action"},
          {"id": 34, "name": "Seinen"},
          {"id": 37, "name": "Supernatural"}
        ],
        "created_at": "2006-03-15T00:00:00+00:00",
        "updated_at": "2024-01-15T00:00:00+00:00",
        "media_type": "manga",
        "status": "currently_publishing",
        "my_list_status": null,
        "num_volumes": 41,
        "num_chapters": 370,
        "authors": [
          {"node": {"id": 1, "first_name": "Kentaro", "last_name": "Miura"}, "role": "Story & Art"}
        ]
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/manga?q=berserk&limit=100&offset=100"
  }
}
```

> Ответ сокращён: показан один объект, массивы укорочены.

## Правила для ИИ-агента

- Не добавлять параметры и поля ответа, отсутствующие в этом документе.
- Для пагинации используй ссылки `paging.next` / `paging.previous`, возвращённые API.
- `media_type` имеет другие значения, чем у аниме (`manga`, `novel`, `one_shot`, `doujinshi`, `manhwa`, `manhua`, `oel`).
- `status` имеет другие значения: `finished`, `currently_publishing`, `not_yet_published`.
- `my_list_status` имеет другие статусы: `reading`, `completed`, `on_hold`, `dropped`, `plan_to_read`.
- `my_list_status` возвращается только при OAuth2 авторизации.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).