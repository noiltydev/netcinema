# MyAnimeList API: `GET /manga/{manga_id}`

> Получение детальной информации о манге по ID.

## Назначение

Получение полной информации о манге, включая картинки, авторов, связанные работы, рекомендации, сериализацию и статус пользователя.

## Endpoint

`GET https://api.myanimelist.net/v2/manga/{manga_id}`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- `client_auth` (Client ID) — `X-MAL-CLIENT-ID: <CLIENT_ID>`

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| manga_id | Да | integer | ID манги. |

## Параметры запроса

| Параметр | По умолчанию | Доступные значения | Описание |
| --- | --- | --- | --- |
| fields | — | string | Поля для возврата. Формат: `field1,field2,nested{sub1,sub2}`. См. список полей ниже. |
| nsfw | false | true, false | Включать NSFW-контент. |

## Доступные поля (fields)

Все поля из [`manga-list.md`](manga-list.md#доступные-поля-fields) плюс дополнительные:

- `pictures` — array of objects `{large?, medium}` (не возвращается в списке)
- `background` — string или null (не возвращается в списке)
- `related_anime` — array of objects `{node, relation_type, relation_type_formatted}` (не возвращается в списке)
- `related_manga` — array of objects `{node, relation_type, relation_type_formatted}` (не возвращается в списке)
- `recommendations` — array of objects `{node, num_recommendations}` (не возвращается в списке)
- `serialization` — array of objects `{node: {id, name}, role}` (не возвращается в списке)

### Детали вложенных объектов

**pictures**: массив объектов с `large` (string или null, URI) и `medium` (string, URI).

**related_anime / related_manga**: массив объектов:
- `node` — объект аниме/манги (рекурсивная схема, базовые поля)
- `relation_type` — string (`sequel`, `prequel`, `alternative_setting`, `alternative_version`, `side_story`, `parent_story`, `summary`, `full_story`)
- `relation_type_formatted` — string (человекочитаемый формат, напр. "Alternative version")

**recommendations**: массив объектов:
- `node` — объект манги (рекурсивная схема)
- `num_recommendations` — integer

**serialization**: массив объектов (журналы, в которых публикуется манга):
- `node` — объект журнала:
  - `id` — integer
  - `name` — string
- `role` — string (роль, напр. "Serialization")

**authors** (также в списке, но с вложенностью):
- массив объектов:
  - `node` — объект автора:
    - `id` — integer
    - `first_name` — string
    - `last_name` — string
  - `role` — string (напр. "Story & Art")

## Структура ответа

Возвращается один объект манги (не обёрнут в `data` массив):

```json
{
  "id": 2,
  "title": "Berserk",
  "main_picture": {...},
  "alternative_titles": {...},
  "start_date": "1989-08-25",
  "end_date": null,
  "synopsis": "...",
  "mean": 9.2,
  "rank": 2,
  "popularity": 5,
  "num_list_users": 500000,
  "num_scoring_users": 450000,
  "nsfw": "black",
  "genres": [...],
  "created_at": "2006-03-15T00:00:00+00:00",
  "updated_at": "2024-01-15T00:00:00+00:00",
  "media_type": "manga",
  "status": "currently_publishing",
  "my_list_status": {...},
  "num_volumes": 41,
  "num_chapters": 370,
  "authors": [...],
  "pictures": [...],
  "background": "...",
  "related_anime": [...],
  "related_manga": [...],
  "recommendations": [...],
  "serialization": [...]
}
```

## Пример запроса

```http
GET https://api.myanimelist.net/v2/manga/2?fields=id,title,main_picture,alternative_titles,start_date,end_date,synopsis,mean,rank,popularity,num_list_users,num_scoring_users,nsfw,created_at,updated_at,media_type,status,genres,my_list_status,num_volumes,num_chapters,authors{first_name,last_name},pictures,background,related_anime,related_manga,recommendations,serialization{name}
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа

```json
{
  "id": 2,
  "title": "Berserk",
  "main_picture": {
    "medium": "https://cdn.myanimelist.net/images/manga/2/173994.jpg",
    "large": "https://cdn.myanimelist.net/images/manga/2/173994l.jpg"
  },
  "alternative_titles": {
    "synonyms": ["Berserk", "ベルセルク"],
    "en": "Berserk",
    "ja": "ベルセルク"
  },
  "start_date": "1989-08-25",
  "end_date": null,
  "synopsis": "Гаттс — наёмник, который сражается с демонами...",
  "mean": 9.2,
  "rank": 2,
  "popularity": 5,
  "num_list_users": 500000,
  "num_scoring_users": 450000,
  "nsfw": "black",
  "genres": [
    {"id": 1, "name": "Action"},
    {"id": 34, "name": "Seinen"},
    {"id": 37, "name": "Supernatural"}
  ],
  "created_at": "2006-03-15T00:00:00+00:00",
  "updated_at": "2024-01-15T00:00:00+00:00",
  "media_type": "manga",
  "status": "currently_publishing",
  "my_list_status": {
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
    "tags": ["masterpiece", "dark fantasy"],
    "comments": "Kentaro Miura's magnum opus",
    "updated_at": "2024-01-15T12:00:00+00:00"
  },
  "num_volumes": 41,
  "num_chapters": 370,
  "authors": [
    {"node": {"id": 1, "first_name": "Kentaro", "last_name": "Miura"}, "role": "Story & Art"}
  ],
  "pictures": [
    {"large": "https://cdn.myanimelist.net/images/manga/2/173994l.jpg", "medium": "https://cdn.myanimelist.net/images/manga/2/173994.jpg"},
    {"large": "https://cdn.myanimelist.net/images/manga/2/173995l.jpg", "medium": "https://cdn.myanimelist.net/images/manga/2/173995.jpg"}
  ],
  "background": "Berserk — манга Кентаро Миуры, одна из самых влиятельных...",
  "related_anime": [
    {
      "node": {"id": 33354, "title": "Berserk: The Golden Age Arc", "main_picture": {...}},
      "relation_type": "adaptation",
      "relation_type_formatted": "Adaptation"
    }
  ],
  "related_manga": [
    {
      "node": {"id": 12345, "title": "Berserk Prototype", "main_picture": {...}},
      "relation_type": "prequel",
      "relation_type_formatted": "Prequel"
    }
  ],
  "recommendations": [
    {
      "node": {"id": 100, "title": "Claymore", "main_picture": {...}},
      "num_recommendations": 25000
    }
  ],
  "serialization": [
    {"node": {"id": 1, "name": "Young Animal"}, "role": "Serialization"}
  ]
}
```

> Ответ сокращён: массивы укорочены, вложенные объекты показаны частично.

## Правила для ИИ-агента

- Не добавлять параметры и поля ответа, отсутствующие в этом документе.
- Поля `pictures`, `background`, `related_anime`, `related_manga`, `recommendations`, `serialization` не возвращаются в списке — запрашивай их явно через `fields`.
- `my_list_status` возвращается только при OAuth2 авторизации.
- `relation_type_formatted` предназначен для отображения, не используй для логики.
- `authors` — массив объектов с `node` (автор) и `role`.
- `serialization` — массив объектов с `node` (журнал) и `role`.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).