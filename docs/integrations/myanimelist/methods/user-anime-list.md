# MyAnimeList API: `GET /users/{user_name}/animelist`

> Получение списка аниме пользователя.

## Назначение

Возвращает список аниме пользователя с их статусами в списке. Поддерживает фильтрацию, сортировку и пагинацию.

## Endpoint

`GET https://api.myanimelist.net/v2/users/{user_name}/animelist`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| user_name | Да | string | Имя пользователя или `@me` (для своего списка, требует OAuth2). |

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| status | — | `watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch` | Фильтр по статусу. Можно указать несколько через запятую? (документация не уточняет — уточнить). Для всех статусов не указывайте. |
| sort | — | `list_score`, `list_updated_at`, `anime_title`, `anime_start_date`, `anime_id` | Сортировка. `anime_id` — Under Development. Порядок: `list_score`/`list_updated_at`/`anime_start_date` — descending; `anime_title`/`anime_id` — ascending. |
| limit | 100 | integer (1–1000) | Максимальное количество результатов. Максимум 1000. |
| offset | 0 | integer | Смещение для пагинации. |
| fields | — | string | Поля для возврата. См. ниже. |
| nsfw | false | true, false | Включать NSFW-контент. |

### Доступные поля (fields)

Поля `node` — те же, что в [`anime-list.md`](anime-list.md#доступные-поля-fields).

Дополнительно в каждом объекте `data` возвращается `list_status` (объект `AnimeListStatus`):

| Поле | Тип | Описание |
| --- | --- | --- |
| status | string или null | `watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch` |
| score | integer | 0–10 |
| num_episodes_watched | integer | 0 или количество просмотренных эпизодов |
| is_rewatching | boolean | Пересматривает ли пользователь |
| start_date | string (date) или null | Дата начала просмотра |
| finish_date | string (date) или null | Дата окончания просмотра |
| priority | integer | Приоритет (0–2) |
| num_times_rewatched | integer | Количество пересмотров |
| rewatch_value | integer | Значение пересмотра (0–5) |
| tags | array of strings | Теги пользователя |
| comments | string | Комментарии пользователя |
| updated_at | string (date-time) | Дата обновления статуса |

Указываются как `list_status{status,score,num_episodes_watched,...}`.

## Структура ответа

```json
{
  "data": [
    {
      "node": { ... },
      "list_status": { ... }
    }
  ],
  "paging": {
    "previous": "https://...",
    "next": "https://..."
  }
}
```

## Пример запроса

```http
GET https://api.myanimelist.net/v2/users/@me/animelist?fields=list_status&limit=4
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
        "alternative_titles": {"synonyms": [], "en": "One Piece", "ja": "ワンピース"},
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
      "list_status": {
        "status": "watching",
        "score": 9,
        "num_episodes_watched": 500,
        "is_rewatching": false,
        "start_date": "2020-01-15",
        "finish_date": null,
        "priority": 1,
        "num_times_rewatched": 0,
        "rewatch_value": 0,
        "tags": ["favorite"],
        "comments": "Best anime",
        "updated_at": "2024-01-15T12:00:00+00:00"
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/users/@me/animelist?fields=list_status&limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект.

## Правила для ИИ-агента

- `user_name=@me` работает только с OAuth2.
- `status` — уточнить, поддерживает ли множественные значения через запятую (документация не говорит).
- `sort=anime_id` помечен как "Under Development".
- Максимальный `limit` — 1000 (больше, чем в других эндпоинтах).
- `list_status` возвращается только если запрошен через `fields=list_status{...}`.
- Для пагинации используй `paging.next` / `paging.previous`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).