# MyAnimeList API: `DELETE /manga/{manga_id}/my_list_status`

> Удаление манги из своего списка.

## Назначение

Удаляет указанную мангу из списка пользователя. Если манги нет в списке — ничего не делает и возвращает `404 Not Found`. Будьте осторожны при повторных попытках.

## Endpoint

`DELETE https://api.myanimelist.net/v2/manga/{manga_id}/my_list_status`

## Авторизация

- `main_auth` (OAuth2, scope `write:users`) — `Authorization: Bearer <ACCESS_TOKEN>`
- **Требует OAuth2**. Client ID (`client_auth`) не поддерживается.

## Параметры пути

| Параметр | Обязательный | Тип | Описание |
| --- | --- | --- | --- |
| manga_id | Да | integer | ID манги. |

## Ответы

| Код | Описание |
| --- | --- |
| 200 | OK — удалено (или не было в списке, но API возвращает 200) |
| 404 | Not Found — манга не была в списке (API может вернуть 404 вместо 200) |

> **Важно**: Документация указывает, что если манга нет в списке, эндпоинт «ничего не делает и возвращает 404 Not Found». Будьте осторожны при ретраях — повторный вызов после 404 может быть неидемпотентен.

Тело ответа при 200: `null` (пустое тело).

## Пример запроса

```http
DELETE https://api.myanimelist.net/v2/manga/2/my_list_status
Authorization: Bearer <MAL_ACCESS_TOKEN>
```

## Пример ответа (200)

```
(null body)
```

## Пример ответа (404)

```json
{
  "error": "not_found",
  "message": "Manga not found in user's list"
}
```

> Формат ошибки может отличаться — опирайся на HTTP-код.

## Правила для ИИ-агента

- Требует OAuth2 авторизации (scope `write:users`).
- При получении 404 не делай автоматических повторов — манга уже отсутствует в списке.
- Ответ 200 имеет пустое тело (`null`).
- Аналогично [`anime-list-status-delete.md`](anime-list-status-delete.md), но для манги.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).