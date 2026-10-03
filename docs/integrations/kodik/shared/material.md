# Material Structure

Structure of material items returned in the `results` array of `/list` and `/search` endpoints.

## Fields

| Field | Description |
|-------|-------------|
| `id` | Unique material ID (e.g., `movie-452654`, `serial-452654`) |
| `title` | Material title |
| `title_orig` | Original title |
| `other_title` | Alternative title (common in anime) |
| `link` | Player link |
| `year` | Release year |
| `kinopoisk_id` | KinoPoisk ID |
| `imdb_id` | IMDb ID |
| `mdl_id` | MyDramaList ID |
| `worldart_link` | World Art link (used instead of ID due to independent section IDs) |
| `shikimori_id` | Shikimori ID (numeric only) |
| `type` | Material type (see [Material Types](../resources/list.md#material-types)) |
| `quality` | Video quality (e.g., `WEB-DL 720p`, `BDRip 720p`) |
| `camrip` | Whether the material is a camrip |
| `lgbt` | Whether the material contains LGBT scenes |
| `translation` | Translation object (see below) |
| `created_at` | Creation date (ISO 8601) |
| `updated_at` | Last update date (ISO 8601) |
| `blocked_countries` | Array of country codes where material is blocked (empty if not blocked) |
| `seasons` | Seasons object (only if `with_seasons` or `with_episodes`/`with_episodes_data` is `true`) |
| `last_season` | Last season number (serials only) |
| `last_episode` | Last episode number (serials only) |
| `episodes_count` | Total episode count (serials only) |
| `blocked_seasons` | Blocked seasons info (serials only). `"all"` = entire season blocked, array = specific episodes blocked, empty object = nothing blocked |
| `screenshots` | Array of screenshot URLs. For serials, screenshots from the first episode. Use `with_episodes_data` for per-episode screenshots |
| `material_data` | KinoPoisk/Shikimori/MyDramaList data (only if `with_material_data` is `true`). See [Material Data](material-data.md) |

## Translation Object

| Field | Description |
|-------|-------------|
| `id` | Translation ID |
| `title` | Translation title (e.g., `Дублированный`, `ColdFilm`) |
| `type` | Translation type: `voice` or `subtitles` |

## Seasons Object

The `seasons` field is an object where keys are season numbers:

```json
{
  "5": {
    "link": "http://kodikplayer.com/season/27856/e3135c7e3536a7951fd1f96e59489176/720p",
    "episodes": {
      "1": "http://kodikplayer.com/seria/119601/09249413a7eb3c03b15df57cd56a051b/720p",
      "2": "http://kodikplayer.com/seria/203940/05e3237674dc90b2c480a21050db57ba/720p"
    }
  }
}
```

When using `with_episodes_data`, episode values are objects instead of strings:

```json
{
  "1": {
    "link": "http://kodikplayer.com/seria/119601/09249413a7eb3c03b15df57cd56a051b/720p",
    "title": "Episode title",
    "screenshots": ["https://i.kodikres.com/screenshots/video/50811/1.jpg"]
  }
}
```

## Blocked Seasons

```json
{
  "5": "all",
  "7": ["1", "2", "3", "5"]
}
```

- `"all"` — all episodes in the season are blocked
- Array — only the listed episodes are blocked
- Empty object `{}` — nothing is blocked
