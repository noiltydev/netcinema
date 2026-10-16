# Общие параметры MyAnimeList API

Этот файл — сводка повторяющихся параметров, извлечённых из документации API. Если описания одного параметра различаются между методами, различия перечислены отдельно; не следует автоматически считать семантику полностью идентичной.

> **О шаблонах путей**: в спецификации MAL путь списка пользователя задан как
> `/users/{user_name}/animelist` и `/users/{user_name}/mangalist`. В этом файле и в
> `parameter-index.md` используется конкретное значение `@me` (`/users/@me/animelist`) —
> это допустимое значение `user_name` для собственного списка, но не имя параметра в схеме.

## `fields`

**Встречается в:** все методы списка и деталей (`/anime`, `/anime/{id}`, `/anime/ranking`, `/anime/season`, `/anime/suggestions`, `/users/@me/animelist`, `/manga`, `/manga/{id}`, `/manga/ranking`, `/users/@me/mangalist`, `/users/@me`)

- **Формат:** строка, поля через запятую; вложенные поля в фигурных скобках.
- **Примеры:**
  - `id,title,main_picture`
  - `my_list_status{status,score,num_episodes_watched}`
  - `authors{first_name,last_name},serialization{name}`
  - `anime_statistics` (только для `/users/@me`)
- **Описание:** По умолчанию API не возвращает все поля. Используй `fields` для запроса только нужных данных. Вложенные объекты указываются через `{}` с перечислением подполей через запятую. Неизвестные поля игнорируются API.
- **Важно:** Всегда запрашивай `fields` явно — не полагайся на набор полей "по умолчанию", он может меняться.

## `limit`

**Встречается в:** все методы списка

- **Тип:** integer
- **По умолчанию:** 100 (для большинства методов)
- **Максимум:**
  - `/anime`, `/manga`, `/anime/suggestions`, `/forum/topic`, `/forum/topics` — 100
  - `/anime/ranking`, `/anime/season`, `/manga/ranking` — 500
  - `/users/@me/animelist`, `/users/@me/mangalist` — 1000
- **Описание:** Максимальное количество элементов в ответе. При превышении максимума API вернёт ошибку 400.

## `offset`

**Встречается в:** все методы списка

- **Тип:** integer
- **По умолчанию:** 0
- **Описание:** Смещение для пагинации. Используй вместе с `limit` для постраничного обхода. Альтернатива — использовать ссылки `paging.next` / `paging.previous` из ответа API.

## `nsfw`

**Встречается в:** все методы списка и деталей

- **Тип:** boolean (`true` / `false`)
- **По умолчанию:** `false`
- **Описание:** По умолчанию API не возвращает NSFW-контент (hentai, explicit content). Установи `nsfw=true` для включения. Значения `nsfw` в ответе: `white` (safe), `gray` (может быть NSFW), `black` (NSFW).

## `status` (фильтр списка пользователя)

**Встречается в:** [`/users/@me/animelist`](../methods/user-anime-list.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)

### Для аниме (`/users/@me/animelist`)

- **Допустимые значения:** `watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch`
- **Описание:** Фильтрует возвращаемый список аниме по статусу в списке пользователя. Документация не уточняет, поддерживает ли множественные значения через запятую — уточнить при необходимости.

### Для манги (`/users/@me/mangalist`)

- **Допустимые значения:** `reading`, `completed`, `on_hold`, `dropped`, `plan_to_read`
- **Описание:** Аналогично аниме, но с терминологией манги (`reading` вместо `watching`, `plan_to_read` вместо `plan_to_watch`).

## `sort`

**Встречается в:** [`/users/@me/animelist`](../methods/user-anime-list.md), [`/users/@me/mangalist`](../methods/user-manga-list.md), [`/anime/season/{year}/{season}`](../methods/anime-seasonal.md), [`/forum/topics`](../methods/forum-topics.md)

### Для `/users/@me/animelist`

| Значение | Порядок | Описание |
| --- | --- | --- |
| `list_score` | Descending | По оценке в списке |
| `list_updated_at` | Descending | По дате обновления статуса |
| `anime_title` | Ascending | По названию аниме |
| `anime_start_date` | Descending | По дате начала аниме |
| `anime_id` | Ascending | По ID аниме (Under Development) |

### Для `/users/@me/mangalist`

| Значение | Порядок | Описание |
| --- | --- | --- |
| `list_score` | Descending | По оценке в списке |
| `list_updated_at` | Descending | По дате обновления статуса |
| `manga_title` | Ascending | По названию манги |
| `manga_start_date` | Descending | По дате начала манги |
| `manga_id` | Ascending | По ID манги (Under Development) |

### Для `/anime/season/{year}/{season}`

| Значение | Порядок | Описание |
| --- | --- | --- |
| `anime_score` | Descending | По средней оценке аниме |
| `anime_num_list_users` | Descending | По числу пользователей в списке |

### Для `/forum/topics`

| Значение | Описание |
| --- | --- |
| `recent` | Только доступное значение (по новизне) |

## `q` (поиск)

**Встречается в:** [`/anime`](../methods/anime-list.md), [`/manga`](../methods/manga-list.md), [`/forum/topics`](../methods/forum-topics.md)

- **Тип:** string
- **Описание:** Поисковый запрос. Поведение может отличаться: для `/anime` и `/manga` — поиск по названию; для `/forum/topics` — поиск по темам/контенту.

## `ranking_type`

**Встречается в:** [`/anime/ranking`](../methods/anime-ranking.md), [`/manga/ranking`](../methods/manga-ranking.md)

- **Обязательный:** да
- **Тип:** string

### Значения для `/anime/ranking`

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

### Значения для `/manga/ranking`

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

## `season` (путь/параметр)

**Встречается в:** [`/anime/season/{year}/{season}`](../methods/anime-seasonal.md) (путь)

- **Тип:** string (enum)
- **Допустимые значения:** `winter`, `spring`, `summer`, `fall`
- **Соответствие месяцам:**
  - `winter` — Январь, Февраль, Март
  - `spring` — Апрель, Май, Июнь
  - `summer` — Июль, Август, Сентябрь
  - `fall` — Октябрь, Ноябрь, Декабрь

## Аутентификация

### OAuth2 (`main_auth`)

- **Тип:** OAuth2 Implicit Flow
- **Authorization URL:** `https://myanimelist.net/v1/oauth2/authorize`
- **Scope:** `write:users` — доступ к профилю и спискам пользователя, публикация от имени пользователя.
- **Заголовок:** `Authorization: Bearer <ACCESS_TOKEN>`
- **Требуется для:** `/anime/suggestions`, `/anime/{id}/my_list_status` (PUT/DELETE), `/manga/{id}/my_list_status` (PUT/DELETE), `/users/@me/animelist`, `/users/@me/mangalist`, `/users/@me`, и для получения `my_list_status` в других эндпоинтах.

### Client ID (`client_auth`)

- **Тип:** API Key (header)
- **Заголовок:** `X-MAL-CLIENT-ID: <CLIENT_ID>`
- **Использование:** Для публичных запросов без контекста пользователя (поиск, рейтинги, сезонные списки, форумы).
- **Не работает для:** `/anime/suggestions`, эндпоинты `my_list_status`, `/users/@me/*`.

## Форматы дат и времени

| Формат | Тип | Пример | Описание |
| --- | --- | --- | --- |
| date-time | string | `2015-03-02T06:03:11+00:00` | ISO 8601 |
| date | string | `2017-10-23`, `2017-10`, `2017` | Дата (год-месяц-день, допускаются частичные) |
| time | string | `01:35` | Время (часы:минуты) |

## Пагинация

Все методы списка возвращают объект `paging`:

```json
{
  "paging": {
    "previous": "https://api.myanimelist.net/v2/...?offset=0&limit=100",
    "next": "https://api.myanimelist.net/v2/...?offset=100&limit=100"
  }
}
```

- `previous` — ссылка на предыдущую страницу (null на первой)
- `next` — ссылка на следующую страницу (null на последней)
- **Правило:** Используй эти ссылки напрямую, не собирай URL вручную.

## Формат ошибок

```json
{
  "error": "invalid_token",
  "message": "token is invalid"
}
```

- `error` — код ошибки (напр. `invalid_token`)
- `message` — человекочитаемое описание

HTTP-коды:
- 400 — Bad Request (неверные параметры)
- 401 — Unauthorized (invalid_token, expired token)
- 403 — Forbidden (DoS и т.п.)
- 404 — Not Found

## Схемы вложенных объектов (часто используемые)

### `main_picture`

```json
{
  "large": "https://.../image_l.jpg",
  "medium": "https://.../image_m.jpg"
}
```
- `large` — string или null (URI)
- `medium` — string (URI)

### `alternative_titles`

```json
{
  "synonyms": ["Synonym 1", "Synonym 2"],
  "en": "English Title",
  "ja": "日本語タイトル"
}
```
- `synonyms` — array of strings или null
- `en` — string или null
- `ja` — string или null

### `genres` (массив)

```json
[{"id": 1, "name": "Action"}, {"id": 2, "name": "Adventure"}]
```
- `id` — integer
- `name` — string

### `studios` / `authors` (массив)

```json
[{"id": 1, "name": "Studio Name"}]
```
- `id` — integer
- `name` — string

### `start_season`

```json
{"year": 2024, "season": "summer"}
```
- `year` — integer
- `season` — string (`winter`, `spring`, `summer`, `fall`)

### `broadcast`

```json
{"day_of_the_week": "sunday", "start_time": "09:30"}
```
- `day_of_the_week` — string (день недели или `other`)
- `start_time` — string или null (формат `HH:MM`)

### `my_list_status` (anime) / `list_status` (user animelist)

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
  "comments": "Great!",
  "updated_at": "2024-01-20T12:00:00+00:00"
}
```

### `my_list_status` (manga) / `list_status` (user mangalist)

```json
{
  "status": "reading",
  "score": 8,
  "num_volumes_read": 5,
  "num_chapters_read": 25,
  "is_rereading": false,
  "start_date": "2024-01-15",
  "finish_date": null,
  "priority": 1,
  "num_times_reread": 0,
  "reread_value": 0,
  "tags": ["favorite"],
  "comments": "Great!",
  "updated_at": "2024-01-20T12:00:00+00:00"
}
```