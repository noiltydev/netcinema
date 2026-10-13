# MyAnimeList API: `GET /forum/topics`

> Получение списка тем форума с фильтрацией.

## Назначение

Возвращает список тем форума с возможностью фильтрации по доске, поддоске, поиску, автору и сортировки.

## Endpoint

`GET https://api.myanimelist.net/v2/forum/topics`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| board_id | — | integer | Фильтр по ID доски (получить через `/forum/boards`). |
| subboard_id | — | integer | Фильтр по ID поддоски. |
| limit | 100 | integer (1–100) | Максимальное количество результатов. Максимум 100. |
| offset | 0 | integer | Смещение для пагинации. |
| sort | `recent` | `recent` | Сортировка. В данный момент только `recent`. |
| q | — | string | Поисковый запрос по названию/содержимому. |
| topic_user_name | — | string | Фильтр по автору темы. |
| user_name | — | string | Фильтр по пользователю (назначение уточнять — возможно, последний пост). |

## Структура ответа

```json
{
  "data": [
    {
      "id": 481,
      "title": "One Piece Discussion",
      "created_at": "2024-01-15T12:00:00+00:00",
      "created_by": {
        "id": 100,
        "name": "luffy_fan"
      },
      "number_of_posts": 150,
      "last_post_created_at": "2024-01-20T18:30:00+00:00",
      "last_post_created_by": {
        "id": 101,
        "name": "zoro_fan"
      },
      "is_locked": false
    }
  ],
  "paging": {
    "previous": "https://...",
    "next": "https://..."
  }
}
```

Поля каждого элемента `data`:
- `id` — integer (ID темы)
- `title` — string
- `created_at` — string (date-time)
- `created_by` — объект:
  - `id` — integer
  - `name` — string
- `number_of_posts` — integer
- `last_post_created_at` — string (date-time)
- `last_post_created_by` — объект:
  - `id` — integer
  - `name` — string
- `is_locked` — boolean

## Пример запроса

```http
GET https://api.myanimelist.net/v2/forum/topics?q=love&subboard_id=2&limit=10
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "data": [
    {
      "id": 481,
      "title": "One Piece Discussion",
      "created_at": "2024-01-15T12:00:00+00:00",
      "created_by": {"id": 100, "name": "luffy_fan"},
      "number_of_posts": 150,
      "last_post_created_at": "2024-01-20T18:30:00+00:00",
      "last_post_created_by": {"id": 101, "name": "zoro_fan"},
      "is_locked": false
    },
    {
      "id": 482,
      "title": "Naruto vs Sasuke",
      "created_at": "2024-01-10T10:00:00+00:00",
      "created_by": {"id": 102, "name": "sasuke_fan"},
      "number_of_posts": 89,
      "last_post_created_at": "2024-01-19T22:15:00+00:00",
      "last_post_created_by": {"id": 103, "name": "naruto_fan"},
      "is_locked": true
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/forum/topics?q=love&subboard_id=2&limit=10&offset=10"
  }
}
```

> Ответ сокращён: показано 2 темы.

## Правила для ИИ-агента

- `board_id` и `subboard_id` можно получить через [`/forum/boards`](forum-boards.md).
- `sort` в данный момент только `recent` — не полагайся на другие значения.
- `limit` максимум 100.
- `topic_user_name` и `user_name` — уточнить различие (документация не специфицирует).
- Для пагинации используй `paging.next` / `paging.previous`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).