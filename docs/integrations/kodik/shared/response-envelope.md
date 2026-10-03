# Response Envelope

All endpoints return a consistent envelope structure.

## Success Response

| Field | Type | Description |
|-------|------|-------------|
| `time` | string | Request processing time (e.g., "5ms") |
| `total` | integer | Total number of matching items |
| `results` | array | Array of result objects |
| `prev_page` | string/null | URL of previous page (list endpoint only) |
| `next_page` | string/null | URL of next page (list endpoint only) |

## Pagination

The `/list` endpoint uses cursor-based pagination:

1. Make initial request without `next` parameter
2. Check `next_page` field in response
3. If not null, make request to `next_page` URL
4. Repeat until `next_page` is null

**Note:** The `next_page` URL includes all original parameters automatically.

## Endpoint-Specific Envelopes

### /list

```json
{
  "time": "5ms",
  "total": 30590,
  "prev_page": null,
  "next_page": "https://kodik-api.com/list?token=TOKEN&next=WyIyMDIzLTEwLTExVDA4OjE2OjUwWiIsIjUxMzk1Il0=",
  "results": []
}
```

### /search

```json
{
  "time": "5ms",
  "total": 27,
  "results": []
}
```

### /translations/v2

```json
{
  "time": "5ms",
  "total": 30590,
  "results": []
}
```
