# MyAnimeList API: `GET /anime/season/{year}/{season}`

> Получение аниме за конкретный сезон.

## Назначение

Получение списка аниме, вышедшего в указанном сезоне года.

## Endpoint

`GET https://api.myanimelist.net/v2/anime/season/{year}/{season}`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| year | Да | integer | Год (например, 2024). |
| season | Да | string | Сезон: `winter`, `spring`, `summer`, `fall`. |

### Сезоны и месяцы

| Сезон | Месяцы |
| --- | --- |
| winter | Январь, Февраль, Март |
| spring | Апрель, Май, Июнь |
| summer | Июль, Август, Сентябрь |
| fall | Октябрь, Ноябрь, Декабрь |

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| sort | — | `anime_score`, `anime_num_list_users` | Сортировка: по оценке (убыв.) или числу пользователей в списке (убыв.). |
| limit | 100 | integer (1–500) | Максимальное количество результатов. Максимум 500. |
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
GET https://api.myanimelist.net/v2/anime/season/2024/summer?limit=4
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "data": [
    {
      "node": {
        "id": 55555,
        "title": "Summer Anime 2024",
        "main_picture": {
          "medium": "https://cdn.myanimelist.net/images/anime/...",
          "large": "https://cdn.myanimelist.net/images/anime/...l.jpg"
        },
        "alternative_titles": {"synonyms": [], "en": "Summer Anime 2024", "ja": "夏アニメ 2024"},
        "start_date": "2024-07-05",
        "end_date": null,
        "synopsis": "Описание аниме...",
        "mean": 7.5,
        "rank": 100,
        "popularity": 500,
        "num_list_users": 10000,
        "num_scoring_users": 8000,
        "nsfw": "white",
        "genres": [{"id": 1, "name": "Action"}],
        "created_at": "2024-01-15T00:00:00+00:00",
        "updated_at": "2024-07-10T00:00:00+00:00",
        "media_type": "tv",
        "status": "currently_airing",
        "my_list_status": null,
        "num_episodes": 12,
        "start_season": {"year": 2024, "season": "summer"},
        "broadcast": {"day_of_the_week": "saturday", "start_time": "23:30"},
        "source": "original",
        "average_episode_duration": 1440,
        "rating": "pg_13",
        "studios": [{"id": 10, "name": "Studio Example"}]
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/anime/season/2024/summer?limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект.

## Правила для ИИ-агента

- Параметры пути `year` и `season` обязательны.
- `season` принимает только 4 значения: `winter`, `spring`, `summer`, `fall`.
- `sort` имеет только два допустимых значения.
- Максимальный `limit` — 500.
- Для пагинации используй `paging.next` / `paging.previous`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).