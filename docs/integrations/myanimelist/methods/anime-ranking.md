# MyAnimeList API: `GET /anime/ranking`

> Получение рейтинга аниме по различным категориям.

## Назначение

Получение списка аниме с рейтингом. Возвращаемые объекты содержат дополнительное поле `ranking`.

## Endpoint

`GET https://api.myanimelist.net/v2/anime/ranking`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры запроса

### Обязательные параметры

| Параметр | Тип | Доступные значения | Описание |
| --- | --- | --- | --- |
| ranking_type | string | `all`, `airing`, `upcoming`, `tv`, `ova`, `movie`, `special`, `bypopularity`, `favorite` | Тип рейтинга. |

### Необязательные параметры

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| limit | 100 | integer (1–500) | Максимальное количество результатов. Максимум 500. |
| offset | 0 | integer | Смещение для пагинации. |
| fields | — | string | Поля для возврата. См. [`anime-list.md`](anime-list.md#доступные-поля-fields). |
| nsfw | false | true, false | Включать NSFW-контент. |

### Значения ranking_type

| Значение | Описание |
| --- | --- |
| `all` | Top Anime Series |
| `airing` | Top Airing Anime |
| `upcoming` | Top Upcoming Anime |
| `tv` | Top Anime TV Series |
| `ova` | Top Anime OVA Series |
| `movie` | Top Anime Movies |
| `special` | Top Anime Specials |
| `bypopularity` | Top Anime by Popularity |
| `favorite` | Top Favorited Anime |

## Структура ответа

```json
{
  "data": [
    {
      "node": { ... },
      "ranking": {
        "rank": 1,
        "previous_rank": 2
      }
    }
  ],
  "paging": {
    "previous": "https://...",
    "next": "https://..."
  }
}
```

- `node` — объект аниме (те же поля, что в [`anime-list.md`](anime-list.md#доступные-поля-fields)).
- `ranking` — объект с информацией о рейтинге:
  - `rank` — integer (текущий ранг)
  - `previous_rank` — integer или null (предыдущий ранг)

> `previous_rank` объявлен в схеме `RankingInfo`, но фактически **не приходит**: его нет ни в
> собственном примере ответа в документации MAL, ни в реальных ответах API. Обрабатывай как null.

## Пример запроса

```http
GET https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4
Authorization: Bearer <MAL_ACCESS_TOKEN>
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
          "synonyms": ["One Piece"],
          "en": "One Piece",
          "ja": "ワンピース"
        },
        "start_date": "1999-10-20",
        "end_date": null,
        "synopsis": "...",
        "mean": 8.73,
        "rank": 10,
        "popularity": 1,
        "num_list_users": 2500000,
        "num_scoring_users": 2000000,
        "nsfw": "white",
        "genres": [{"id": 2, "name": "Adventure"}],
        "created_at": "2006-03-15T00:00:00+00:00",
        "updated_at": "2024-01-15T00:00:00+00:00",
        "media_type": "tv",
        "status": "currently_airing",
        "my_list_status": null,
        "num_episodes": 1100,
        "start_season": {"year": 1999, "season": "fall"},
        "broadcast": {"day_of_the_week": "sunday", "start_time": "09:30"},
        "source": "manga",
        "average_episode_duration": 1440,
        "rating": "pg_13",
        "studios": [{"id": 1, "name": "Toei Animation"}]
      },
      "ranking": {
        "rank": 1,
        "previous_rank": 2
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/anime/ranking?ranking_type=all&limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект.

## Правила для ИИ-агента

- Параметр `ranking_type` обязателен.
- Поле `ranking` добавляется к каждому объекту в `data`.
- Для пагинации используй `paging.next` / `paging.previous`.
- Максимальный `limit` — 500 (в отличие от `/anime` — 100).
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).