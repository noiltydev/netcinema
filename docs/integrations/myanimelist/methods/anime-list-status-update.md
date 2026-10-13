# MyAnimeList API: `PUT /anime/{anime_id}/my_list_status`

> Добавление аниме в свой список или обновление статуса.

## Назначение

Добавляет указанное аниме в список пользователя. Если аниме уже есть в списке — обновляет его статус. Обновляются только переданные параметры.

## Endpoint

`PUT https://api.myanimelist.net/v2/anime/{anime_id}/my_list_status`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- **Требует OAuth2**. Client ID (`client_auth`) не поддерживается.

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| anime_id | Да | integer | ID аниме. |

## Параметры тела запроса (application/x-www-form-urlencoded)

Все параметры необязательные. Передаются как form-data.

| Параметр | Тип | Доступные значения | Описание |
| --- | --- | --- | --- |
| status | string | `watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch` | Статус просмотра. |
| is_rewatching | boolean | `true`, `false` | Пересматривает ли пользователь. Если `true`, MAL считает статус `watching`. |
| score | integer | 0–10 | Оценка (0–10). |
| num_watched_episodes | integer | ≥0 | Количество просмотренных эпизодов. |
| priority | integer | 0–2 | Приоритет (0–2). |
| num_times_rewatched | integer | ≥0 | Количество пересмотров. |
| rewatch_value | integer | 0–5 | Значение пересмотра (0–5). |
| tags | string | — | Теги (строка, формат не специфицирован). |
| comments | string | — | Комментарии. |

## Структура ответа

Возвращает обновлённый объект `my_list_status`:

```json
{
  "status": "watching",
  "score": 8,
  "num_episodes_watched": 10,
  "is_rewatching": false,
  "start_date": "2024-01-15",
  "finish_date": null,
  "priority": 1,
  "num_times_rewatched": 0,
  "rewatch_value": 0,
  "tags": ["favorite"],
  "comments": "Great anime!",
  "updated_at": "2024-01-20T12:00:00+00:00"
}
```

Поля:
- `status` — string или null (`watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch`)
- `score` — integer (0–10)
- `num_episodes_watched` — integer
- `is_rewatching` — boolean
- `start_date` — string (date) или null
- `finish_date` — string (date) или null
- `priority` — integer
- `num_times_rewatched` — integer
- `rewatch_value` — integer
- `tags` — array of strings
- `comments` — string
- `updated_at` — string (date-time)

## Пример запроса

```http
PUT https://api.myanimelist.net/v2/anime/30230/my_list_status
Authorization: Bearer <MAL_ACCESS_TOKEN>
Content-Type: application/x-www-form-urlencoded

status=completed&score=9&num_watched_episodes=1100
```

## Пример ответа

```json
{
  "status": "completed",
  "score": 9,
  "num_episodes_watched": 1100,
  "is_rewatching": false,
  "start_date": "2020-01-15",
  "finish_date": "2024-01-20",
  "priority": 1,
  "num_times_rewatched": 0,
  "rewatch_value": 0,
  "tags": ["favorite", "masterpiece"],
  "comments": "One of the best anime ever made",
  "updated_at": "2024-01-20T15:30:00+00:00"
}
```

## Правила для ИИ-агента

- Требует OAuth2 авторизации (scope `write:users`).
- Обновляются только переданные параметры — не нужно отправлять все поля.
- `Content-Type: application/x-www-form-urlencoded` обязателен.
- Если аниме не было в списке — оно добавляется.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).