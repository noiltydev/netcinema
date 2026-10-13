# MyAnimeList API: `GET /forum/topic/{topic_id}`

> Получение деталей темы форума с постами.

## Назначение

Возвращает информацию о теме форума и список постов с пагинацией. Поддерживает опросы.

## Endpoint

`GET https://api.myanimelist.net/v2/forum/topic/{topic_id}`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| topic_id | Да | integer | ID темы форума. |

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| limit | 100 | integer (1–100) | Максимальное количество постов. Максимум 100. |
| offset | 0 | integer | Смещение для пагинации. |

## Структура ответа

```json
{
  "data": [
    {
      "title": "Topic Title",
      "posts": [
        {
          "id": 1,
          "number": 1,
          "created_at": "2024-01-15T12:00:00+00:00",
          "created_by": {
            "id": 100,
            "name": "username",
            "forum_avatar": "https://..."
          },
          "body": "Post content",
          "signature": "User signature"
        }
      ],
      "poll": [
        {
          "id": 1,
          "question": "Favorite anime?",
          "close": false,
          "options": [
            {"id": 1, "text": "One Piece", "votes": 100},
            {"id": 2, "text": "Naruto", "votes": 80}
          ]
        }
      ],
      "paging": {
        "previous": "https://...",
        "next": "https://..."
      }
    }
  ]
}
```

Поля:
- `data` — массив (обычно один элемент) с объектом темы:
  - `title` — string
  - `posts` — массив постов:
    - `id` — integer
    - `number` — integer (номер поста в теме)
    - `created_at` — string (date-time)
    - `created_by` — объект:
      - `id` — integer
      - `name` — string
      - `forum_avatar` — string (URI)
    - `body` — string
    - `signature` — string
  - `poll` — массив опросов (может быть null/пустым):
    - `id` — integer
    - `question` — string
    - `close` — boolean
    - `options` — массив вариантов:
      - `id` — integer
      - `text` — string
      - `votes` — integer
  - `paging` — пагинация постов:
    - `previous` — string или null
    - `next` — string или null

## Пример запроса

```http
GET https://api.myanimelist.net/v2/forum/topic/481
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "data": [
    {
      "title": "One Piece Discussion",
      "posts": [
        {
          "id": 1001,
          "number": 1,
          "created_at": "2024-01-15T12:00:00+00:00",
          "created_by": {
            "id": 100,
            "name": "luffy_fan",
            "forum_avatar": "https://cdn.myanimelist.net/images/avatars/100.jpg"
          },
          "body": "One Piece is the best anime ever!",
          "signature": "Pirate King incoming"
        },
        {
          "id": 1002,
          "number": 2,
          "created_at": "2024-01-15T12:30:00+00:00",
          "created_by": {
            "id": 101,
            "name": "zoro_fan",
            "forum_avatar": "https://cdn.myanimelist.net/images/avatars/101.jpg"
          },
          "body": "Agreed, the world building is incredible.",
          "signature": "Three swords style"
        }
      ],
      "poll": [
        {
          "id": 1,
          "question": "Who is the strongest Straw Hat?",
          "close": false,
          "options": [
            {"id": 1, "text": "Luffy", "votes": 150},
            {"id": 2, "text": "Zoro", "votes": 120},
            {"id": 3, "text": "Sanji", "votes": 80}
          ]
        }
      ],
      "paging": {
        "previous": null,
        "next": "https://api.myanimelist.net/v2/forum/topic/481?limit=100&offset=100"
      }
    }
  ]
}
```

> Ответ сокращён: показаны 2 поста из возможного большего количества.

## Правила для ИИ-агента

- `limit` максимум 100.
- Посты пагинируются через `paging.next` / `paging.previous`.
- `poll` может отсутствовать или быть пустым массивом.
- `forum_avatar` — ссылка на аватар на форуме.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).