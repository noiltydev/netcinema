# Индекс параметров MyAnimeList API

Этот файл помогает быстро находить параметры, встречающиеся в нескольких методах. Подробное описание конкретного параметра в первую очередь сверяйте с документацией метода: одинаковое имя не гарантирует полностью одинаковый контекст.

## Общие параметры (встречаются в большинстве методов)

- `fields` — все методы списка и деталей
- `limit` — все методы списка
- `offset` — все методы списка
- `nsfw` — все методы списка и деталей
- `status` — [`/users/@me/animelist`](../methods/user-anime-list.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `sort` — [`/users/@me/animelist`](../methods/user-anime-list.md), [`/users/@me/mangalist`](../methods/user-manga-list.md), [`/anime/season/{year}/{season}`](../methods/anime-seasonal.md), [`/forum/topics`](../methods/forum-topics.md)
- `q` — [`/anime`](../methods/anime-list.md), [`/manga`](../methods/manga-list.md), [`/forum/topics`](../methods/forum-topics.md)

## Параметры аниме

- `ranking_type` — [`/anime/ranking`](../methods/anime-ranking.md)
- `year`, `season` — [`/anime/season/{year}/{season}`](../methods/anime-seasonal.md)
- `anime_id` (path) — [`/anime/{anime_id}`](../methods/anime-details.md), [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-delete.md)

## Параметры манги

- `ranking_type` — [`/manga/ranking`](../methods/manga-ranking.md) (другие значения, чем у аниме)
- `manga_id` (path) — [`/manga/{manga_id}`](../methods/manga-details.md), [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-delete.md)

## Параметры списка пользователя (animelist)

- `status` — `watching`, `completed`, `on_hold`, `dropped`, `plan_to_watch` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `is_rewatching` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md)
- `score` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md)
- `num_watched_episodes` / `num_episodes_watched` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `priority` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `num_times_rewatched` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `rewatch_value` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `tags` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `comments` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `start_date`, `finish_date` — [`/anime/{anime_id}/my_list_status`](../methods/anime-list-status-update.md), [`/users/@me/animelist`](../methods/user-anime-list.md)
- `updated_at` — [`/users/@me/animelist`](../methods/user-anime-list.md)

## Параметры списка пользователя (mangalist)

- `status` — `reading`, `completed`, `on_hold`, `dropped`, `plan_to_read` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `is_rereading` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md)
- `score` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md)
- `num_volumes_read` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `num_chapters_read` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `priority` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `num_times_reread` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `reread_value` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `tags` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `comments` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `start_date`, `finish_date` — [`/manga/{manga_id}/my_list_status`](../methods/manga-list-status-update.md), [`/users/@me/mangalist`](../methods/user-manga-list.md)
- `updated_at` — [`/users/@me/mangalist`](../methods/user-manga-list.md)

## Параметры форума

- `topic_id` (path) — [`/forum/topic/{topic_id}`](../methods/forum-topic.md)
- `board_id` — [`/forum/topics`](../methods/forum-topics.md)
- `subboard_id` — [`/forum/topics`](../methods/forum-topics.md)
- `topic_user_name` — [`/forum/topics`](../methods/forum-topics.md)
- `user_name` — [`/forum/topics`](../methods/forum-topics.md)

## Параметры пользователя

- `user_id` (path, только `@me`) — [`/users/{user_id}`](../methods/user-info.md)
- `anime_statistics` (в fields) — [`/users/{user_id}`](../methods/user-info.md)

## Аутентификация

- `Authorization: Bearer <ACCESS_TOKEN>` (OAuth2, scope `write:users`) — все методы, требующие `main_auth`
- `X-MAL-CLIENT-ID: <CLIENT_ID>` (Client ID) — методы с поддержкой `client_auth` (не все методы!)