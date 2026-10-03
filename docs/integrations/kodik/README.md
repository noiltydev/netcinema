# Kodik API

Base URL: `https://kodik-api.com`

## Authentication

All requests require a valid API token passed as the `token` query parameter.

**Configuration:**

The Kodik API token is configured via environment variables in `config/noilty.php`:

```php
'kodik' => [
    'key' => env('KODIK_API_KEY'),
    'endpoint' => env('KODIK_API_ENDPOINT', 'https://kodik-api.com'),
],
```

**Environment Variables:**

| Variable | Required | Default | Description |
|----------|----------|---------|-------------|
| `KODIK_API_KEY` | Yes | — | Your Kodik API token |
| `KODIK_API_ENDPOINT` | No | `https://kodik-api.com` | API base URL |

**Example:**
```
https://kodik-api.com/list?token=YOUR_TOKEN_HERE
```

**Token security:**
- Never expose tokens in client-side code
- Rotate tokens periodically
- Use environment variables in server-side integrations

## Endpoints

| Endpoint | Description |
|----------|-------------|
| [`/list`](resources/list.md) | Returns a paginated list of all materials matching criteria |
| [`/search`](resources/search.md) | Returns materials matching a search query, sorted by relevance |
| [`/translations/v2`](resources/translations.md) | Returns a list of all available translations (озвучки) |

## Shared Documentation

- [Common Filters](shared/filters.md) — KinoPoisk/Shikimori/MyDramaList filter parameters available on all endpoints
- [Material Structure](shared/material.md) — Structure of material items in `results[]`
- [Material Data](shared/material-data.md) — Structure of the `material_data` field (KinoPoisk/Shikimori/MyDramaList info)
