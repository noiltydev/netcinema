# Kodik API

Base URL: `https://kodik-api.com`

## Authentication

All requests require a `token` parameter. Obtain a token from your Kodik account settings.

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
