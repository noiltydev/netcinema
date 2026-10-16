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
| 200 | OK — манга была в списке и удалена |
| 404 | Not Found — манги не было в списке; эндпоинт ничего не сделал |

> **Важно**: по документации MAL, если манги нет в списке, эндпоинт «ничего не делает и возвращает
> 404 Not Found». Будьте осторожны при ретраях — повторный вызов после 404 не имеет смысла,
> запись уже отсутствует.

Тело ответа при 200: `null` (пустое тело).

Схема тела ошибки для 404 в спецификации **не описана** — ориентируйся на HTTP-код, а не на содержимое ответа.

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

Тело ответа не специфицировано. Общий формат ошибки MAL:

```json
{
  "error": "invalid_token",
  "message": "token is invalid"
}
```

> Для 404 конкретные коды ошибки не задокументированы — не рассчитывай на конкретные значения.

## Правила для ИИ-агента

- Требует OAuth2 авторизации (scope `write:users`).
- При получении 404 не делай автоматических повторов — манга уже отсутствует в списке.
- Ответ 200 имеет пустое тело (`null`).
- Аналогично [`anime-list-status-delete.md`](anime-list-status-delete.md), но для манги.
- Для общих параметров сверяться с [`../parameters/common.md`](../parameters/common.md) и [`../parameters/parameter-index.md`](../parameters/parameter-index.md).