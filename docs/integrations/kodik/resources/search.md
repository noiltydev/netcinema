# /search

Returns a list of materials matching the search query, sorted by relevance. Results are automatically sorted by the latest episode and season.

**Endpoint:** `https://kodik-api.com/search`

## Parameters

### Required

| Parameter | Description |
|-----------|-------------|
| `token` | API token |

### At Least One Required

| Parameter | Values | Description |
|-----------|--------|-------------|
| `title` | Any string | Search by title (also searches `title_orig`, `other_title`, and external source fields) |
| `title_orig` | Any string | Search by original title only |
| `id` | `movie-123123`, `serial-123123` | Search by Kodik ID |
| `player_link` | URL | Search by any player link |
| `kinopoisk_id` | 0–999999999 | Search by KinoPoisk ID |
| `imdb_id` | `tt0`–`tt999999999` | Search by IMDb ID |
| `mdl_id` | `123456`, `123456-title` | Search by MyDramaList ID |
| `worldart_animation_id` | 0–999999999 | Search by World Art anime ID |
| `worldart_cinema_id` | 0–999999999 | Search by World Art cinema ID |
| `worldart_link` | URL | Search by full World Art link |
| `shikimori_id` | 0–999999999 | Search by Shikimori ID |

> **Note:** When passing multiple IDs from the list above (except Kodik ID), all materials matching at least one ID are returned, sorted by number of matching IDs (most relevant first).

### Search Modifiers

| Parameter | Default | Values | Description |
|-----------|---------|--------|-------------|
| `strict` | `false` | `true`, `false` | If `true`, word order must match exactly (extra words still allowed) |
| `full_match` | `false` | `true`, `false` | If `true`, title must match exactly (case-insensitive, no extra words) |

### Optional

| Parameter | Default | Values | Description |
|-----------|---------|--------|-------------|
| `limit` | — | 1–100 | Maximum number of materials to return |
| `types` | All types | See [Material Types](list.md#material-types) | Filter by material type |
| `year` | — | `1982`, `1982,2020` | Filter by year |
| `translation_id` | — | `714`, `714,720` | Filter by translation ID |
| `translation_type` | — | `voice`, `subtitles` | Filter by translation type |
| `has_field` | — | `kinopoisk_id`, `imdb_id`, `mdl_id`, `worldart_link`, `shikimori_id` | Filter by field presence (at least one) |
| `prioritize_translations` | `704,734` | `123,123`, `subtitles`, `voice` | Boost priority of specific translations. Pass `0` to disable defaults |
| `unprioritize_translations` | `800,882,subtitles` | `123,123`, `subtitles`, `voice` | Lower priority of specific translations. Pass `0` to disable defaults |
| `prioritize_translation_type` | `voice` | `voice`, `subtitles` | Boost priority of translation type |
| `block_translations` | — | `714`, `714,720` | Exclude specific translations from results |
| `camrip` | — | `true`, `false` | Filter by camrip status |
| `lgbt` | — | `true`, `false` | Filter by LGBT content |
| `with_seasons` | `false` | `true`, `false` | Include seasons in response |
| `season` | — | `1`, `5` | Filter by specific season (auto-enables `with_seasons`) |
| `with_episodes` | `false` | `true`, `false` | Include episodes with links |
| `with_episodes_data` | `false` | `true`, `false` | Include episodes with full data (link, title, screenshots) |
| `episode` | — | `1`, `5` | Filter by specific episode (requires `season`, auto-enables `with_episodes`) |
| `with_page_links` | `false` | `true`, `false` | Replace player links with page links |
| `not_blocked_in` | — | `RU,UA`, `RU` | Filter by countries where material is NOT blocked |
| `not_blocked_for_me` | — | `true`, `false` | Auto-detect country and exclude blocked materials |

## Common Filters

This endpoint also supports all [Common Filters](shared/filters.md) (KinoPoisk/Shikimori/MyDramaList).

## Response Structure

| Field | Description |
|-------|-------------|
| `time` | Request processing time |
| `total` | Total number of matching materials |
| `results` | Array of [material items](shared/material.md) |

## Examples

### Search by title

```
https://kodik-api.com/search?token=YOUR_TOKEN&title=Аватар%20смотреть%20онлайн%20бесплатно
```

```json
{
  "time": "5ms",
  "total": 27,
  "results": [
    {
      "id": "movie-452654",
      "type": "foreign-movie",
      "link": "http://kodikplayer.com/video/93/2fc1d3b9759726b2bc5b104b225d2b4a/720p",
      "title": "Аватар",
      "title_orig": "Avatar",
      "translation": {
        "id": 704,
        "title": "Дублированный",
        "type": "voice"
      },
      "year": 2009,
      "kinopoisk_id": "251733",
      "imdb_id": "tt0499549",
      "quality": "BDRip 720p",
      "blocked_countries": [],
      "created_at": "2014-06-22T22:19:22Z",
      "updated_at": "2016-04-25T07:03:33Z",
      "screenshots": [
        "https://i.kodikres.com/screenshots/video/50811/1.jpg",
        "https://i.kodikres.com/screenshots/video/50811/2.jpg"
      ]
    }
  ]
}
```

### Search by KinoPoisk ID with episodes

```
https://kodik-api.com/search?token=YOUR_TOKEN&kinopoisk_id=1161904&with_episodes=true&with_material_data=true
```

```json
{
  "time": "5ms",
  "total": 9,
  "results": [
    {
      "id": "serial-452654",
      "type": "anime",
      "link": "http://kodikplayer.com/serial/4309/bc6def495a31545c7f648f7fb68d22a8/720p",
      "title": "Игра престолов",
      "title_orig": "Game of Thrones",
      "translation": {
        "id": 611,
        "title": "ColdFilm",
        "type": "voice"
      },
      "year": 2011,
      "last_season": 9,
      "last_episode": 4,
      "episodes_count": 119,
      "kinopoisk_id": "1161904",
      "imdb_id": "tt0944947",
      "quality": "WEB-DL 720p",
      "blocked_countries": ["RU"],
      "blocked_seasons": {
        "5": "all",
        "7": ["1", "2", "3", "5"]
      },
      "created_at": "2017-07-17T16:34:52Z",
      "updated_at": "2018-04-06T14:19:27Z",
      "seasons": {
        "5": {
          "link": "http://kodikplayer.com/season/27856/e3135c7e3536a7951fd1f96e59489176/720p",
          "episodes": {
            "1": "http://kodikplayer.com/seria/119601/09249413a7eb3c03b15df57cd56a051b/720p",
            "2": "http://kodikplayer.com/seria/203940/05e3237674dc90b2c480a21050db57ba/720p"
          }
        }
      },
      "material_data": {
        "title": "Игра престолов",
        "title_en": "Game of Thrones",
        "year": 2011,
        "tagline": "Победа или смерть",
        "description": "К концу подходит время благоденствия...",
        "poster_url": "https://st.kp.yandex.net/images/film_iphone/iphone360_464963.jpg",
        "duration": 55,
        "countries": ["США", "Великобритания"],
        "genres": ["фэнтези", "боевик", "драма"],
        "kinopoisk_rating": 9,
        "kinopoisk_votes": 268732,
        "imdb_rating": 9.4,
        "imdb_votes": 1553335,
        "premiere_ru": "2011-09-08",
        "premiere_world": "2011-04-17",
        "actors": ["Питер Динклэйдж", "Лина Хиди", "Эмилия Кларк"],
        "directors": ["Дэвид Наттер", "Алан Тейлор"],
        "producers": ["Дэвид Бениофф", "Д.Б. Уайсс"],
        "writers": ["Дэвид Бениофф", "Джордж Р.Р. Мартин"],
        "composers": ["Рамин Джавади"],
        "editors": ["Кэти Вейланд", "Фрэнсис Паркер"],
        "designers": ["Дебора Райли", "Джемма Джексон"],
        "operators": ["Анетт Хаелльмигк", "Джонатан Фриман"]
      },
      "screenshots": [
        "https://i.kodikres.com/screenshots/video/50811/1.jpg",
        "https://i.kodikres.com/screenshots/video/50811/2.jpg"
      ]
    }
  ]
}
```
