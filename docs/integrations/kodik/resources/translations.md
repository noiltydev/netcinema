# /translations/v2

Returns a list of all available translations (озвучки), optionally filtered by material criteria.

**Endpoint:** `https://kodik-api.com/translations/v2`

## Parameters

### Required

| Parameter | Description |
|-----------|-------------|
| `token` | API token |

### Optional

| Parameter | Default | Values | Description |
|-----------|---------|--------|-------------|
| `types` | All types | See [Material Types](list.md#material-types) | Filter by material type |
| `year` | — | 0000–9999 | Filter by year |
| `translation_type` | — | `voice`, `subtitles` | Filter by translation type |
| `has_field` | — | `kinopoisk_id`, `imdb_id`, `mdl_id`, `worldart_link`, `shikimori_id` | Filter by field presence (at least one) |
| `lgbt` | — | `true`, `false` | Filter by LGBT content |
| `sort` | `title` | `title`, `count` | Sort by translation title or material count |

## Common Filters

This endpoint also supports all [Common Filters](shared/filters.md) (KinoPoisk/Shikimori/MyDramaList).

## Response Structure

| Field | Description |
|-------|-------------|
| `time` | Request processing time |
| `total` | Total number of translations |
| `results` | Array of translation objects |

### Translation Object

| Field | Description |
|-------|-------------|
| `id` | Translation ID |
| `title` | Translation title |
| `count` | Number of materials with this translation |

## Examples

### Get translations for anime serials

```
https://kodik-api.com/translations/v2?token=YOUR_TOKEN&types=anime-serial
```

```json
{
  "time": "5ms",
  "total": 30590,
  "results": [
    {
      "id": 735,
      "title": "2x2",
      "count": 26
    },
    {
      "id": 824,
      "title": "3df voice",
      "count": 16
    }
  ]
}
```
