# MyAnimeList API: `GET /users/{user_id}`

> Получение информации о пользователе.

## Назначение

Возвращает профиль пользователя и (опционально) статистику по аниме.

## Endpoint

`GET https://api.myanimelist.net/v2/users/{user_id}`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- **Требует OAuth2**. Client ID (`client_auth`) не поддерживается.

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| user_id | Да | string | Только `@me` (свой профиль). |

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| fields | — | string | Поля для возврата. Доступно: `anime_statistics`. |

### Поле `anime_statistics`

Если запросить `fields=anime_statistics`, в ответе добавляется объект:

```json
{
  "anime_statistics": {
    "num_items_watching": 10,
    "num_items_completed": 200,
    "num_items_on_hold": 5,
    "num_items_dropped": 10,
    "num_items_plan_to_watch": 50,
    "num_items": 275,
    "num_days_watched": 150.5,
    "num_days_watching": 30.2,
    "num_days_completed": 100.1,
    "num_days_on_hold": 5.5,
    "num_days_dropped": 2.3,
    "num_days": 138.1,
    "num_episodes": 5000,
    "num_times_rewatched": 3,
    "mean_score": 7.85
  }
}
```

Поля:
- `num_items_watching` — integer
- `num_items_completed` — integer
- `num_items_on_hold` — integer
- `num_items_dropped` — integer
- `num_items_plan_to_watch` — integer
- `num_items` — integer (сумма всех)
- `num_days_watched` — number (float)
- `num_days_watching` — number (float)
- `num_days_completed` — number (float)
- `num_days_on_hold` — number (float)
- `num_days_dropped` — number (float)
- `num_days` — number (float) — сумма дней по статусам
- `num_episodes` — integer (всего просмотренных эпизодов)
- `num_times_rewatched` — integer
- `mean_score` — number (float) — средняя оценка

## Структура ответа (базовая)

```json
{
  "id": 123456,
  "name": "username",
  "picture": "https://cdn.myanimelist.net/images/userimages/...",
  "gender": "Male",
  "birthday": "1990-01-01",
  "location": "Tokyo, Japan",
  "joined_at": "2015-06-15T12:00:00+00:00",
  "time_zone": "Asia/Tokyo",
  "is_supporter": true,
  "anime_statistics": { ... }
}
```

Поля:
- `id` — integer (int32)
- `name` — string
- `picture` — string (URI)
- `gender` — string или null (`Male`, `Female`, `Non-Binary` и т.д.)
- `birthday` — string (date) или null
- `location` — string или null
- `joined_at` — string (date-time)
- `time_zone` — string или null (IANA timezone, напр. `America/Los_Angeles`)
- `is_supporter` — boolean или null
- `anime_statistics` — object (только если запрошен через `fields`)

## Пример запроса

```http
GET https://api.myanimelist.net/v2/users/@me?fields=anime_statistics
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "id": 123456,
  "name": "luffy_fan",
  "picture": "https://cdn.myanimelist.net/images/userimages/123456.jpg",
  "gender": "Male",
  "birthday": "1995-05-05",
  "location": "Tokyo, Japan",
  "joined_at": "2015-06-15T12:00:00+00:00",
  "time_zone": "Asia/Tokyo",
  "is_supporter": true,
  "anime_statistics": {
    "num_items_watching": 5,
    "num_items_completed": 150,
    "num_items_on_hold": 3,
    "num_items_dropped": 10,
    "num_items_plan_to_watch": 25,
    "num_items": 193,
    "num_days_watched": 85.5,
    "num_days_watching": 12.3,
    "num_days_completed": 65.2,
    "num_days_on_hold": 3.1,
    "num_days_dropped": 1.2,
    "num_days": 81.8,
    "num_episodes": 3500,
    "num_times_rewatched": 2,
    "mean_score": 8.1
  }
}
```

## Правила для ИИ-агента

- `user_id` может быть только `@me` — другие значения не поддерживаются.
- Требует OAuth2 авторизации (scope `write:users`).
- `anime_statistics` возвращается только при явном запросе `fields=anime_statistics`.
- `time_zone` — IANA timezone identifier (напр. `America/Los_Angeles`, `Asia/Tokyo`).
- `is_supporter` — показывает, является ли пользователь сторонником MAL (подписка).
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).