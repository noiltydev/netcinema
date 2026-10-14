# MyAnimeList API: `PUT /manga/{manga_id}/my_list_status`

> Добавление манги в свой список или обновление статуса.

## Назначение

Добавляет указанную мангу в список пользователя. Если манга уже есть в списке — обновляет её статус. Обновляются только переданные параметры.

## Endpoint

`PUT https://api.myanimelist.net/v2/manga/{manga_id}/my_list_status`

> **Неоднозначность в документации MAL**: в OpenAPI-спецификации операция объявлена как **PATCH**
> (`operation/manga_manga_id_my_list_status_put` с HTTP-методом `patch`), тогда как пример curl
> в самой документации MAL использует `-X PUT`. Оба варианта встречаются в разных частях
> официальной документации; какой из них работает на практике — не проверено.
> Перед реализацией проверь фактический метод запроса.

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- **Требует OAuth2**. Client ID (`client_auth`) не поддерживается.

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| manga_id | Да | integer | ID манги. |

## Параметры тела запроса (application/x-www-form-urlencoded)

Все параметры необязательные. Передаются как form-data.

| Параметр | Тип | Доступные значения | Описание |
| --- | --- | --- | --- |
| status | string | `reading`, `completed`, `on_hold`, `dropped`, `plan_to_read` | Статус чтения. |
| is_rereading | boolean | `true`, `false` | Перечитывает ли пользователь. Если `true`, MAL считает статус `reading`. |
| score | integer | 0–10 | Оценка (0–10). |
| num_volumes_read | integer | ≥0 | Количество прочитанных томов. |
| num_chapters_read | integer | ≥0 | Количество прочитанных глав. |
| priority | integer | 0–2 | Приоритет (0–2). |
| num_times_reread | integer | ≥0 | Количество перечтений. |
| reread_value | integer | 0–5 | Значение перечтения (0–5). |
| tags | string | — | Теги (строка, формат не специфицирован). |
| comments | string | — | Комментарии. |

## Структура ответа

Возвращает обновлённый объект `my_list_status`:

```json
{
  "status": "reading",
  "score": 8,
  "num_volumes_read": 10,
  "num_chapters_read": 50,
  "is_rereading": false,
  "start_date": "2024-01-15",
  "finish_date": null,
  "priority": 1,
  "num_times_reread": 0,
  "reread_value": 0,
  "tags": ["favorite"],
  "comments": "Great manga!",
  "updated_at": "2024-01-20T12:00:00+00:00"
}
```

Поля:
- `status` — string или null (`reading`, `completed`, `on_hold`, `dropped`, `plan_to_read`)
- `score` — integer (0–10)
- `num_volumes_read` — integer
- `num_chapters_read` — integer
- `is_rereading` — boolean
- `start_date` — string (date) или null
- `finish_date` — string (date) или null
- `priority` — integer
- `num_times_reread` — integer
- `reread_value` — integer
- `tags` — array of strings
- `comments` — string
- `updated_at` — string (date-time)

## Пример запроса

```http
PUT https://api.myanimelist.net/v2/manga/2/my_list_status
Authorization: Bearer <MAL_ACCESS_TOKEN>
Content-Type: application/x-www-form-urlencoded

status=completed&score=10&num_volumes_read=41&num_chapters_read=370
```

## Пример ответа

```json
{
  "status": "completed",
  "score": 10,
  "num_volumes_read": 41,
  "num_chapters_read": 370,
  "is_rereading": false,
  "start_date": "2020-01-15",
  "finish_date": "2024-01-20",
  "priority": 1,
  "num_times_reread": 1,
  "reread_value": 5,
  "tags": ["masterpiece", "dark fantasy"],
  "comments": "Kentaro Miura's magnum opus",
  "updated_at": "2024-01-20T15:30:00+00:00"
}
```

## Правила для ИИ-агента

- Требует OAuth2 авторизации (scope `write:users`).
- HTTP-метод неоднозначен: спецификация говорит `PATCH`, пример curl в документации MAL — `PUT`. Проверь перед реализацией.
- Обновляются только переданные параметры — не нужно отправлять все поля.
- `Content-Type: application/x-www-form-urlencoded` обязателен.
- Если манга не была в списке — она добавляется.
- `status` значения отличаются от аниме: `reading` вместо `watching`, `plan_to_read` вместо `plan_to_watch`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).