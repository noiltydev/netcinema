# Common Filters

These filter parameters are available on all Kodik API endpoints (`/list`, `/search`, `/translations/v2`).

## KinoPoisk / Shikimori / MyDramaList Filters

| Parameter | Values | Description |
|-----------|--------|-------------|
| `with_material_data` | `true`, `false` | Include `material_data` field with KinoPoisk/Shikimori/MyDramaList info. See [Material Data](material-data.md) |
| `countries` | `США`, `США,Россия` | Filter by country (case-sensitive, at least one). Use `countries_and` for all |
| `genres` | `биография`, `биография,боевик` | Filter by KinoPoisk genre (case-insensitive, at least one). Use `genres_and` for all |
| `anime_genres` | `биография`, `биография,боевик` | Filter by Shikimori genre (case-insensitive, at least one). Use `anime_genres_and` for all |
| `drama_genres` | `биография`, `биография,боевик` | Filter by MyDramaList genre (case-insensitive, at least one). Use `drama_genres_and` for all |
| `all_genres` | `биография`, `биография,боевик` | Filter by any genre source (case-insensitive, at least one). Use `all_genres_and` for all |
| `duration` | `30`, `40-80` | Filter by duration in minutes (exact or range) |
| `kinopoisk_rating` | `7.0`, `6`, `6.5-8.2` | Filter by KinoPoisk rating (exact or range) |
| `imdb_rating` | `7.0`, `6`, `6.5-8.2` | Filter by IMDb rating (exact or range) |
| `shikimori_rating` | `7.0`, `6`, `6.5-8.2` | Filter by Shikimori rating (exact or range) |
| `mydramalist_rating` | `7.0`, `6`, `6.5-8.2` | Filter by MyDramaList rating (exact or range) |
| `actors` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by actors (case-insensitive, at least one). Use `actors_and` for all |
| `directors` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by directors (case-insensitive, at least one). Use `directors_and` for all |
| `producers` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by producers (case-insensitive, at least one). Use `producers_and` for all |
| `writers` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by writers (case-insensitive, at least one). Use `writers_and` for all |
| `composers` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by composers (case-insensitive, at least one). Use `composers_and` for all |
| `editors` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by editors (case-insensitive, at least one). Use `editors_and` for all |
| `designers` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by designers (case-insensitive, at least one). Use `designers_and` for all |
| `operators` | `Крис Хемсворт`, `Крис Хемсворт,Марк Руффало` | Filter by operators (case-insensitive, at least one). Use `operators_and` for all |
| `rating_mpaa` | `G`, `PG`, `PG-13`, `R`, `R+`, `Rx`, `R, PG-13` | Filter by MPAA rating (case-insensitive) |
| `minimal_age` | `16`, `12-16` | Filter by minimum age (exact or range) |
| `anime_kind` | `tv`, `movie`, `ova`, `ona`, `special`, `music`, `tv_13`, `tv_24`, `tv_48` | Filter by anime type (at least one) |
| `mydramalist_tags` | `Friendship`, `Friendship,Violence` | Filter by MyDramaList tags (at least one). Use `mydramalist_tags_and` for all |
| `anime_status` | `anons`, `ongoing`, `released` | Filter by Shikimori status (at least one) |
| `drama_status` | `anons`, `ongoing`, `released` | Filter by MyDramaList status (at least one) |
| `all_status` | `anons`, `ongoing`, `released` | Filter by any status source (at least one) |
| `anime_studios` | `J.C.Staff`, `Studio Hibari` | Filter by anime studio (at least one). Use `anime_studios_and` for all |
| `anime_licensed_by` | `Wakanim`, `Русский Репортаж` | Filter by license owner (at least one). Use `anime_licensed_by_and` for all |
