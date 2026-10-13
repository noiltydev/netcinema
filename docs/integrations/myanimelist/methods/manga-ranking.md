# MyAnimeList API: `GET /manga/ranking`

> Получение рейтинга манги по различным категориям.

## Назначение

Получение списка манги с рейтингом. Возвращаемые объекты содержат дополнительное поле `ranking`.

## Endpoint

`GET https://api.myanimelist.net/v2/manga/ranking`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры запроса

### Обязательные параметры

| Параметр | Тип | Доступные значения | Описание |
| --- | --- | --- | --- |
| ranking_type | string | `all`, `manga`, `novels`, `oneshots`, `doujin`, `manhwa`, `manhua`, `bypopularity`, `favorite` | Тип рейтинга. |

### Необязательные параметры

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| limit | 100 | integer (1–500) | Максимальное количество результатов. Максимум 500. |
| offset | 0 | integer | Смещение для пагинации. |
| fields | — | string | Поля для возврата. См. [`manga-list.md`](manga-list.md#доступные-поля-fields). |
| nsfw | false | true, false | Включать NSFW-контент. |

### Значения ranking_type

| Значение | Описание |
| --- | --- |
| `all` | All |
| `manga` | Top Manga |
| `novels` | Top Novels |
| `oneshots` | Top One-shots |
| `doujin` | Top Doujinshi |
| `manhwa` | Top Manhwa |
| `manhua` | Top Manhua |
| `bypopularity` | Most Popular |
| `favorite` | Most Favorited |

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

- `node` — объект манги (те же поля, что в [`manga-list.md`](manga-list.md#доступные-поля-fields)).
- `ranking` — объект с информацией о рейтинге:
  - `rank` — integer (текущий ранг)
  - `previous_rank` — integer или null (предыдущий ранг)

## Пример запроса

```http
GET https://api.myanimelist.net/v2/manga/ranking?ranking_type=all&limit=4
Authorization: Bearer <MAL_ACCESS_TOKEN>
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
        "alternative_titles": {"synonyms": [], "en": "Berserk", "ja": "ベルセルク"},
        "start_date": "1989-08-25",
        "end_date": null,
        "synopsis": "...",
        "mean": 9.2,
        "rank": 2,
        "popularity": 5,
        "num_list_users": 500000,
        "num_scoring_users": 450000,
        "nsfw": "black",
        "genres": [{"id": 1, "name": "Action"}],
        "created_at": "2006-03-15T00:00:00+00:00",
        "updated_at": "2024-01-15T00:00:00+00:00",
        "media_type": "manga",
        "status": "currently_publishing",
        "my_list_status": null,
        "num_volumes": 41,
        "num_chapters": 370,
        "authors": [{"node": {"id": 1, "first_name": "Kentaro", "last_name": "Miura"}, "role": "Story & Art"}]
      },
      "ranking": {
        "rank": 1,
        "previous_rank": 2
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/manga/ranking?ranking_type=all&limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект.

## Правила для ИИ-агента

- Параметр `ranking_type` обязателен.
- Поле `ranking` добавляется к каждому объекту в `data`.
- Для пагинации используй `paging.next` / `paging.previous`.
- Максимальный `limit` — 500.
- Значения `ranking_type` отличаются от аниме (нет `tv`, `ova`, `movie`, `special`, `airing`, `upcoming`).
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).