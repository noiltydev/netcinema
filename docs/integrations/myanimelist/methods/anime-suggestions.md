# MyAnimeList API: `GET /anime/suggestions`

> Получение рекомендованного аниме для авторизованного пользователя.

## Назначение

Возвращает список рекомендованного аниме на основе истории просмотра пользователя. Для новых пользователей возвращает пустой список.

## Endpoint

`GET https://api.myanimelist.net/v2/anime/suggestions`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- **Требует OAuth2**. Client ID (`client_auth`) не поддерживается.

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| limit | 100 | integer (1–100) | Максимальное количество результатов. Максимум 100. |
| offset | 0 | integer | Смещение для пагинации. |
| fields | — | string | Поля для возврата. См. [`anime-list.md`](anime-list.md#доступные-поля-fields). |
| nsfw | false | true, false | Включать NSFW-контент. |

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

- `node` — объект аниме (те же поля, что в [`anime-list.md`](anime-list.md#доступные-поля-fields)).

## Пример запроса

```http
GET https://api.myanimelist.net/v2/anime/suggestions?limit=4
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "data": [
    {
      "node": {
        "id": 21,
        "title": "Naruto",
        "main_picture": {
          "medium": "https://cdn.myanimelist.net/images/anime/13/17405.jpg",
          "large": "https://cdn.myanimelist.net/images/anime/13/17405l.jpg"
        },
        "alternative_titles": {"synonyms": [], "en": "Naruto", "ja": "ナルト"},
        "start_date": "2002-10-03",
        "end_date": "2007-02-08",
        "synopsis": "Моменты перед смертью...",
        "mean": 7.9,
        "rank": 50,
        "popularity": 10,
        "num_list_users": 2000000,
        "num_scoring_users": 1800000,
        "nsfw": "white",
        "genres": [{"id": 1, "name": "Action"}, {"id": 2, "name": "Adventure"}],
        "created_at": "2006-03-15T00:00:00+00:00",
        "updated_at": "2024-01-15T00:00:00+00:00",
        "media_type": "tv",
        "status": "finished_airing",
        "my_list_status": null,
        "num_episodes": 220,
        "start_season": {"year": 2002, "season": "fall"},
        "broadcast": {"day_of_the_week": "wednesday", "start_time": "18:30"},
        "source": "manga",
        "average_episode_duration": 1440,
        "rating": "pg_13",
        "studios": [{"id": 3, "name": "Pierrot"}]
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/anime/suggestions?limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект. Для новых пользователей `data` — пустой массив.

## Правила для ИИ-агента

- Требует OAuth2 авторизации (scope `write:users`). Client ID не работает.
- Для новых пользователей возвращает пустой список `data: []`.
- Максимальный `limit` — 100.
- Для пагинации используй `paging.next` / `paging.previous`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).