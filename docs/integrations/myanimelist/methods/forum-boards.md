# MyAnimeList API: `GET /forum/boards`

> Получение списка форумов (категорий, досок, поддосок).

## Назначение

Возвращает иерархию форумов: категории → доски → поддоски.

## Endpoint

`GET https://api.myanimelist.net/v2/forum/boards`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры запроса

Нет параметров запроса.

## Структура ответа

```json
{
  "categories": [
    {
      "title": "Anime",
      "boards": [
        {
          "id": 1,
          "title": "General Anime",
          "description": "Обсуждение аниме в целом",
          "subboards": [
            {
              "id": 10,
              "title": "Recommendations"
            }
          ]
        }
      ]
    }
  ]
}
```

Поля:
- `categories` — массив категорий
  - `title` — string (название категории)
  - `boards` — массив досок
    - `id` — integer
    - `title` — string
    - `description` — string
    - `subboards` — массив поддосок
      - `id` — integer
      - `title` — string

## Пример запроса

```http
GET https://api.myanimelist.net/v2/forum/boards
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "categories": [
    {
      "title": "Anime",
      "boards": [
        {
          "id": 1,
          "title": "General Anime",
          "description": "Discuss anime in general",
          "subboards": [
            {"id": 10, "title": "Recommendations"},
            {"id": 11, "title": "Identification"}
          ]
        },
        {
          "id": 2,
          "title": "Specific Anime",
          "description": "Discuss specific anime series",
          "subboards": []
        }
      ]
    },
    {
      "title": "Manga",
      "boards": [
        {
          "id": 3,
          "title": "General Manga",
          "description": "Discuss manga in general",
          "subboards": [
            {"id": 12, "title": "Recommendations"}
          ]
        }
      ]
    }
  ]
}
```

> Ответ сокращён: показана часть структуры.

## Правила для ИИ-агента

- Эндпоинт не принимает параметров запроса.
- Структура вложенная: категории → доски → поддоски.
- Поддоски могут быть пустым массивом.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).