# MyAnimeList API: `GET /users/{user_name}/mangalist`

> Получение списка манги пользователя.

## Назначение

Возвращает список манги пользователя с их статусами в списке. Поддерживает фильтрацию, сортировку и пагинацию.

## Endpoint

`GET https://api.myanimelist.net/v2/users/{user_name}/mangalist`

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
| status | — | `reading`, `completed`, `on_hold`, `dropped`, `plan_to_read` | Фильтр по статусу. Можно указать несколько через запятую? (документация не уточняет — уточнить). Для всех статусов не указывайте. |
| sort | — | `list_score`, `list_updated_at`, `manga_title`, `manga_start_date`, `manga_id` | Сортировка. `manga_id` — Under Development. Порядок: `list_score`/`list_updated_at`/`manga_start_date` — descending; `manga_title`/`manga_id` — ascending. |
| limit | 100 | integer (1–1000) | Максимальное количество результатов. Максимум 1000. |
| offset | 0 | integer | Смещение для пагинации. |
| fields | — | string | Поля для возврата. См. ниже. |
| nsfw | false | true, false | Включать NSFW-контент. |

### Доступные поля (fields)

Поля `node` — те же, что в [`manga-list.md`](manga-list.md#доступные-поля-fields).

Дополнительно в каждом объекте `data` возвращается `list_status` (объект `MangaListStatus`):

| Поле | Тип | Описание |
| --- | --- | --- |
| status | string или null | `reading`, `completed`, `on_hold`, `dropped`, `plan_to_read` |
| score | integer | 0–10 |
| num_volumes_read | integer | 0 или количество прочитанных томов |
| num_chapters_read | integer | 0 или количество прочитанных глав |
| is_rereading | boolean | Перечитывает ли пользователь |
| start_date | string (date) или null | Дата начала чтения |
| finish_date | string (date) или null | Дата окончания чтения |
| priority | integer | Приоритет (0–2) |
| num_times_reread | integer | Количество перечтений |
| reread_value | integer | Значение перечтения (0–5) |
| tags | array of strings | Теги пользователя |
| comments | string | Комментарии пользователя |
| updated_at | string (date-time) | Дата обновления статуса |

Указываются как `list_status{status,score,num_volumes_read,...}`.

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
GET https://api.myanimelist.net/v2/users/@me/mangalist?fields=list_status&limit=4
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
      "list_status": {
        "status": "reading",
        "score": 10,
        "num_volumes_read": 41,
        "num_chapters_read": 370,
        "is_rereading": false,
        "start_date": "2020-01-15",
        "finish_date": null,
        "priority": 1,
        "num_times_reread": 1,
        "reread_value": 5,
        "tags": ["masterpiece"],
        "comments": "Best manga ever",
        "updated_at": "2024-01-15T12:00:00+00:00"
      }
    }
  ],
  "paging": {
    "previous": null,
    "next": "https://api.myanimelist.net/v2/users/@me/mangalist?fields=list_status&limit=4&offset=4"
  }
}
```

> Ответ сокращён: показан один объект.

## Правила для ИИ-агента

- `user_name=@me` работает только с OAuth2.
- `status` — уточнить, поддерживает ли множественные значения через запятую (документация не говорит).
- `sort=manga_id` помечен как "Under Development".
- Максимальный `limit` — 1000 (больше, чем в других эндпоинтах).
- `list_status` возвращается только если запрошен через `fields=list_status{...}`.
- Значения `status` отличаются от аниме: `reading`/`plan_to_read` вместо `watching`/`plan_to_watch`.
- Поля `list_status`: `num_volumes_read`/`num_chapters_read` вместо `num_episodes_watched`, `is_rereading` вместо `is_rewatching`, `num_times_reread`/`reread_value` вместо `num_times_rewatched`/`rewatch_value`.
- Для пагинации используй `paging.next` / `paging.previous`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).