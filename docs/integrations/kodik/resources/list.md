# /list

Returns a paginated list of all materials matching the given criteria, or all materials if no criteria are specified.

**Endpoint:** `https://kodik-api.com/list`

## Parameters

### Required

| Parameter | Description |
|-----------|-------------|
| `token` | API token |

### Optional

| Parameter | Default | Values | Description |
|-----------|---------|--------|-------------|
| `limit` | 50 | 1–100 | Number of materials per page |
| `sort` | `updated_at` | `year`, `created_at`, `updated_at`, `kinopoisk_rating`, `imdb_rating`, `shikimori_rating` | Field to sort by |
| `order` | `desc` | `asc`, `desc` | Sort direction |
| `types` | All types | See [Material Types](#material-types) | Filter by material type. Multiple values comma-separated |
| `year` | — | `1982`, `1982,2020` | Filter by year |
| `translation_id` | — | `714`, `714,720` | Filter by translation ID |
| `block_translations` | — | `714`, `714,720` | Exclude specific translations from results |
| `translation_type` | — | `voice`, `subtitles` | Filter by translation type |
| `has_field` | — | `kinopoisk_id`, `imdb_id`, `mdl_id`, `worldart_link`, `shikimori_id` | Filter by field presence (at least one) |
| `camrip` | — | `true`, `false` | Filter by camrip status |
| `lgbt` | — | `true`, `false` | Filter by LGBT content |
| `with_seasons` | `false` | `true`, `false` | Include seasons in response |
| `with_episodes` | `false` | `true`, `false` | Include episodes with links |
| `with_episodes_data` | `false` | `true`, `false` | Include episodes with full data (link, title, screenshots) |
| `with_page_links` | `false` | `true`, `false` | Replace player links with page links |
| `not_blocked_in` | — | `RU,UA`, `RU` | Filter by countries where material is NOT blocked |
| `not_blocked_for_me` | — | `true`, `false` | Auto-detect country and exclude blocked materials |

### Material Types

**Movies:** `foreign-movie`, `soviet-cartoon`, `foreign-cartoon`, `russian-cartoon`, `anime`, `russian-movie`

**Serials:** `cartoon-serial`, `documentary-serial`, `russian-serial`, `foreign-serial`, `anime-serial`, `multi-part-film`

## Common Filters

This endpoint also supports all [Common Filters](shared/filters.md) (KinoPoisk/Shikimori/MyDramaList).

## Response Structure

| Field | Description |
|-------|-------------|
| `time` | Request processing time |
| `total` | Total number of matching materials |
| `prev_page` | URL of previous page (or `null`) |
| `next_page` | URL of next page (or `null`) |
| `results` | Array of [material items](shared/material.md) |

## Examples

### Basic request

```
https://kodik-api.com/list?token=YOUR_TOKEN
```

```json
{
  "time": "5ms",
  "total": 30590,
  "prev_page": null,
  "next_page": "https://kodik-api.com/list?token=YOUR_TOKEN&next=WyIyMDIzLTEwLTExVDA4OjE2OjUwWiIsIjUxMzk1Il0=",
  "results": [
    {
      "id": "movie-452654",
      "type": "foreign-movie",
      "link": "http://kodikplayer.com/video/19850/6476310cc6d90aa9304d5d8af3a91279/720p",
      "title": "Спортлото-82",
      "title_orig": "Спортлото-82",
      "translation": {
        "id": 703,
        "title": "Не требуется",
        "type": "voice"
      },
      "year": 1982,
      "kinopoisk_id": "43949",
      "imdb_id": "tt0084716",
      "quality": "HDTVRip 720p",
      "blocked_countries": [],
      "created_at": "2017-12-03T09:12:49Z",
      "updated_at": "2018-04-10T08:25:23Z",
      "screenshots": [
        "https://i.kodikres.com/screenshots/video/50811/1.jpg",
        "https://i.kodikres.com/screenshots/video/50811/2.jpg"
      ]
    }
  ]
}
```

### With episodes and material data

```
https://kodik-api.com/list?token=YOUR_TOKEN&types=anime-serial,foreign-serial&with_episodes=true&with_material_data=true
```

```json
{
  "time": "5ms",
  "total": 30590,
  "prev_page": null,
  "next_page": "https://kodik-api.com/list?token=YOUR_TOKEN&types=anime-serial%2Cforeign-serial&with_episodes=true&with_material_data=true&next=WyIyMDIzLTEwLTExVDA3OjM2OjIwWiIsIjUyNDYzIl0=",
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
