# Интеграция MyAnimeList API

Документация для ИИ-агентов и разработчиков, реализующих интеграцию с MyAnimeList API в этом проекте.

## С чего начать

1. Прочитай [`AGENTS.md`](AGENTS.md) — обязательные правила работы с интеграцией.
2. Открой описание нужного endpoint в [`methods/`](methods/).
3. Для параметров, используемых несколькими endpoint, проверь [`parameters/parameter-index.md`](parameters/parameter-index.md), затем [`parameters/common.md`](parameters/common.md).
4. Если описание endpoint уточняет общий параметр или задаёт особое поведение, приоритет имеет описание endpoint.
5. Не придумывай поля ответа, значения параметров, ограничения и поведение, которых нет в документации. При нехватке данных явно укажи неопределённость.

## Endpoint'ы

### Anime

| Endpoint | Назначение | Документация |
|---|---|---|
| `GET /anime` | Получение списка аниме | [anime-list.md](methods/anime-list.md) |
| `GET /anime/{anime_id}` | Получение деталей аниме | [anime-details.md](methods/anime-details.md) |
| `GET /anime/ranking` | Получение рейтинга аниме | [anime-ranking.md](methods/anime-ranking.md) |
| `GET /anime/season/{year}/{season}` | Получение сезонного аниме | [anime-seasonal.md](methods/anime-seasonal.md) |
| `GET /anime/suggestions` | Получение рекомендованного аниме | [anime-suggestions.md](methods/anime-suggestions.md) |

### User AnimeList

| Endpoint | Назначение | Документация |
|---|---|---|
| `PUT /anime/{anime_id}/my_list_status` | Обновление статуса аниме в моём списке | [anime-list-status-update.md](methods/anime-list-status-update.md) |
| `DELETE /anime/{anime_id}/my_list_status` | Удаление аниме из моего списка | [anime-list-status-delete.md](methods/anime-list-status-delete.md) |
| `GET /users/{user_name}/animelist` | Получение списка аниме пользователя | [user-anime-list.md](methods/user-anime-list.md) |

### Forum

| Endpoint | Назначение | Документация |
|---|---|---|
| `GET /forum/boards` | Получение списка форумов | [forum-boards.md](methods/forum-boards.md) |
| `GET /forum/topic/{topic_id}` | Получение деталей темы форума | [forum-topic.md](methods/forum-topic.md) |
| `GET /forum/topics` | Получение списка тем форума | [forum-topics.md](methods/forum-topics.md) |

### Manga

| Endpoint | Назначение | Документация |
|---|---|---|
| `GET /manga` | Получение списка манги | [manga-list.md](methods/manga-list.md) |
| `GET /manga/{manga_id}` | Получение деталей манги | [manga-details.md](methods/manga-details.md) |
| `GET /manga/ranking` | Получение рейтинга манги | [manga-ranking.md](methods/manga-ranking.md) |

### User MangaList

| Endpoint | Назначение | Документация |
|---|---|---|
| `PUT /manga/{manga_id}/my_list_status` | Обновление статуса манги в моём списке | [manga-list-status-update.md](methods/manga-list-status-update.md) |
| `DELETE /manga/{manga_id}/my_list_status` | Удаление манги из моего списка | [manga-list-status-delete.md](methods/manga-list-status-delete.md) |
| `GET /users/{user_name}/mangalist` | Получение списка манги пользователя | [user-manga-list.md](methods/user-manga-list.md) |

### User

| Endpoint | Назначение | Документация |
|---|---|---|
| `GET /users/{user_id}` | Получение информации о пользователе | [user-info.md](methods/user-info.md) |

## Базовые сведения

- Базовый адрес API: `https://api.myanimelist.net/v2`
- Версия API: v2 (beta)
- Аутентификация: OAuth2 (scope `write:users`) или Client ID через заголовок `X-MAL-CLIENT-ID`
- Формат ответа: JSON
- Пагинация: через параметры `limit` и `offset`, ссылки `next`/`previous` в поле `paging`
- Формат дат: ISO 8601 (`2015-03-02T06:03:11+00:00`), дата (`2017-10-23`, `2017-10`, `2017`), время (`01:35`)
- Выбор полей: параметр `fields` с перечислением через запятую, вложенные поля в фигурных скобках (например, `my_list_status{priority,comments}`)
- NSFW: параметр `nsfw` (`true`/`false`) для включения контента 18+

## Коды состояния

| Код | Описание |
|---|---|
| 200 | OK |
| 400 | Bad Request — неверные параметры |
| 401 | Unauthorized — неверный/истёкший токен (`invalid_token`) |
| 403 | Forbidden — DoS и т.п. |
| 404 | Not Found |

## Примеры запросов и ответов

Каждый файл в `methods/` содержит примеры HTTP-запроса и JSON-ответа. Токен в примерах заменён на `<MAL_ACCESS_TOKEN>` или `<MAL_CLIENT_ID>`: используйте секрет из конфигурации окружения. Примеры ответов могут быть сокращены, что отмечено рядом с ними.