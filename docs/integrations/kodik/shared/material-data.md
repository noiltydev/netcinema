# Material Data

Structure of the `material_data` field, containing information from KinoPoisk, Shikimori, and MyDramaList. This field is only present when `with_material_data=true` is passed and data is available for the material.

> **Note:** If no data is available from any source for a material, the `material_data` field is omitted entirely. Individual sub-fields are also omitted when data is unavailable (e.g., if there is no actor info, the `actors` field is absent).

## Fields

| Field | Description | Source | Example |
|-------|-------------|--------|---------|
| `title` | Title | KinoPoisk, Shikimori | `"Аватар"` |
| `anime_title` | Anime title | Shikimori | `"Аватар"` |
| `title_en` | Original title | KinoPoisk, Shikimori, MyDramaList | `"Avatar"` |
| `other_titles` | Other titles (array) | Shikimori, MyDramaList | `["Аватар", "Аватар 2"]` |
| `other_titles_en` | Other titles in English (array) | Shikimori | `["Avatar", "Avatar 2"]` |
| `other_titles_jp` | Other titles in Japanese (array) | Shikimori | `["アバター", "アバター 2"]` |
| `anime_license_name` | License name in Russia | Shikimori | `"Аватар"` |
| `anime_licensed_by` | License owners (array) | Shikimori | `["Wakanim", "Русский Репортаж"]` |
| `anime_kind` | Anime type | Shikimori | `"ova"` |
| `mydramalist_tags` | MyDramaList tags | MyDramaList | `["Friendship", "Violence"]` |
| `all_status` | Material status | Shikimori, MyDramaList | `"released"` |
| `anime_status` | Anime status | Shikimori | `"released"` |
| `drama_status` | Drama status | MyDramaList | `"released"` |
| `year` | Year | KinoPoisk | `2016` |
| `tagline` | Tagline | KinoPoisk | `"An entire universe. Once and for all"` |
| `description` | Description | KinoPoisk, Shikimori | `"Пока Мстители и их союзники..."` |
| `anime_description` | Anime description | Shikimori | `"Пока Мстители и их союзники..."` |
| `poster_url` | Poster URL | KinoPoisk, Shikimori, MyDramaList | `"https://st.kp.yandex.net/images/film_iphone/iphone360_840471.jpg"` |
| `anime_poster_url` | Anime poster URL | Shikimori | `"https://shikimori.io/system/animes/original/35683.jpg"` |
| `drama_poster_url` | Drama poster URL | MyDramaList | `"https://i.mydramalist.com/dpOZz_4f.jpg"` |
| `screenshots` | Screenshot URLs (array) | Shikimori | `["https://site.com/image1.png"]` |
| `duration` | Duration in minutes | KinoPoisk, Shikimori, MyDramaList | `160` |
| `countries` | Countries (array) | KinoPoisk, MyDramaList | `["США", "Великобритания"]` |
| `all_genres` | All genres (array) | KinoPoisk, Shikimori, MyDramaList | `["комедия", "боевик"]` |
| `genres` | Genres (array) | KinoPoisk | `["комедия", "боевик"]` |
| `anime_genres` | Anime genres (array) | Shikimori | `["приключения", "комедия"]` |
| `drama_genres` | Drama genres (array) | MyDramaList | `["приключения", "комедия"]` |
| `anime_studios` | Anime studios (array) | Shikimori | `["Studio Deen"]` |
| `kinopoisk_rating` | KinoPoisk rating | KinoPoisk | `7.2` |
| `kinopoisk_votes` | KinoPoisk rating votes | KinoPoisk | `723856` |
| `imdb_rating` | IMDb rating | KinoPoisk | `7.2` |
| `imdb_votes` | IMDb rating votes | KinoPoisk | `723856` |
| `shikimori_rating` | Shikimori rating | Shikimori | `7.2` |
| `shikimori_votes` | Shikimori rating votes | Shikimori | `723856` |
| `mydramalist_rating` | MyDramaList rating | MyDramaList | `7.2` |
| `mydramalist_votes` | MyDramaList rating votes | MyDramaList | `723856` |
| `premiere_ru` | Russia premiere date | KinoPoisk | `"2018-04-16"` |
| `premiere_world` | World premiere date | KinoPoisk | `"2018-04-16"` |
| `aired_at` | Air start date | Shikimori, MyDramaList | `"2018-04-16"` |
| `released_at` | Air end date | Shikimori, MyDramaList | `"2018-04-16"` |
| `next_episode_at` | Next episode air time | Shikimori, MyDramaList | `"2021-04-06T14:19:27Z"` |
| `rating_mpaa` | MPAA rating | KinoPoisk, Shikimori | `"PG-13"` |
| `minimal_age` | Minimum viewing age | KinoPoisk, MyDramaList, Shikimori (from MPAA) | `16` |
| `episodes_total` | Total episode count | Shikimori, MyDramaList | `14` |
| `episodes_aired` | Aired episode count | Shikimori, MyDramaList | `14` |
| `actors` | Actors (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `directors` | Directors (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `producers` | Producers (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `writers` | Writers (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `composers` | Composers (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `editors` | Editors (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `designers` | Designers (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
| `operators` | Operators (array) | KinoPoisk, MyDramaList | `["Роберт Дауни мл.", "Крис Хемсворт"]` |
