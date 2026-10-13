# Общие параметры Kodik API

Этот файл — сводка повторяющихся параметров, извлечённых из предоставленных HTML-файлов. Если описания одного параметра различаются между методами, различия перечислены отдельно; не следует автоматически считать семантику полностью идентичной.

## `actors, directors, producers, writers, composers, editors, designers, operators`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md), [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** Крис Хемсворт , Крис Хемсворт,Марк Руффало
- **Описание:** Фильтрация материалов по персонам. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы одна из указанных персон). Параметр регистронезависимый. Можно указывать фильтры сразу по нескольким профессиям. Чтобы показывались только материалы, у которых есть все указанные персоны , добавьте к параметру _and в конце. Например: actors_and .

## `anime_kind`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** tv , movie , ova , ona , special , music , tv_13 , tv_24 , tv_48 , movie,ova
- **Описание:** Фильтрация материалов по аниме типу. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы один из перечисленных типов).

## `anime_licensed_by`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** Wakanim , Русский Репортаж , Wakanim,Русский Репортаж
- **Описание:** Фильтрация материалов по владельцу лицензии. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы один из перечисленных владельцев). Чтобы показывались только материалы, у которых есть все указанные владельцы , используйте параметр anime_licensed_by_and .

## `anime_status, drama_status, all_status`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** anons , ongoing , released , ongoing,released
- **Описание:** Фильтрация материалов по статусу Shikimori, MyDramaList или по всем статусам. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы один из перечисленных статусов).

## `anime_studios`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** J.C.Staff , Studio Hibari , J.C.Staff,Studio Hibari
- **Описание:** Фильтрация материалов по аниме студии. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы одна из перечисленных студий). Чтобы показывались только материалы, у которых есть все указанные студии , используйте параметр anime_studios_and .

## `block_translations`

**Встречается в:** [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Необязательные параметры
- **По умолчанию:** Отсутствует
- **Доступные значения:** 714 , 714,720
- **Описание:** Удаление определенных озвучек из результатов поиска. ID перечисляются через запятую. ID всех озвучек можно получить через API ресурс /translations либо на странице списка озвучек

## [countries](../methods/countries.md)

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** США , США,Россия
- **Описание:** Фильтрация материалов по стране. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы одна из перечисленных стран). Параметр регистрозависимый. Чтобы показывались только материалы, у которых есть все указанные страны , используйте параметр countries_and .

## `duration`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** 30 , 40-80
- **Описание:** Фильтрация материалов по продолжительности (в минутах). Указать можно как единичное значение для поиска точной продолжительности, так и интервал.

## `genres, anime_genres, drama_genres, all_genres`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** биография , биография,боевик
- **Описание:** Фильтрация материалов по жанру. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы один из указаных жанров). Искать можно по жанрам с КиноПоиск, Shikimori, MyDramaList, либо сразу по всем жанрам. Параметр регистронезависимый. Чтобы показывались только материалы, у которых есть все указанные жанры , добавьте к параметру _and в конце. Например: genres_and .

## `has_field`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Необязательные параметры
- **По умолчанию:** Отсутствует
- **Доступные значения:** kinopoisk_id , imdb_id , mdl_id , worldart_link , shikimori_id
- **Описание:** Фильтрация материалов по наличию у них определенного поля. Показываются материалы, у которых есть хотя бы одно из перечисленных полей. Для того чтобы показывались только материалы, у которых есть все перечисленные поля, нужно использовать параметр has_field_and .

## `kinopoisk_rating, imdb_rating, shikimori_rating, mydramalist_rating`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** 7.0 , 6 , 6.5-8.2
- **Описание:** Фильтрация материалов по рейтингу КиноПоиска, IMDb, Shikimori или MyDramaList. Указать можно как единичное значение для поиска точного рейтинга, так и интервал.

## `lgbt`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Необязательные параметры
- **По умолчанию:** Отсутствует
- **Доступные значения:** true , false
- **Описание:** Фильтрация материалов по параметру lgbt. Если указать false, то будут выведены только материалы не содержащие LGBT сцен. Если не указывать этот параметр, то будут выведены все материалы.

## `minimal_age`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** 16 , 12-16
- **Описание:** Фильтрация материалов по минимальному возрасту, с которого можно смотреть. Можно указать как одно значение, так и диапазон значений.

## `mydramalist_tags`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** Friendship , Friendship,Violence
- **Описание:** Фильтрация материалов по тегам MyDramaList. Можно указать как одно значение, так и несколько значений через запятую (тогда будут выведены материалы, у которых есть хотя бы один из перечисленных типов). Чтобы показывались только материалы, у которых есть все указанные теги , используйте параметр mydramalist_tags_and .

## `rating_mpaa`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Фильтрация по информации с КиноПоиска, Shikimori, MyDramaList
- **По умолчанию:** Отсутствует
- **Доступные значения:** G , PG , PG-13 , R , R+ , Rx , R, PG-13
- **Описание:** Фильтрация материалов по возрастному рейтингу. Можно указать как одно значение, так и несколько значений через запятую. Параметр регистронезависимый.

## `sort`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md),  [years](../methods/years.md)

Описание параметра различается между методами:

- **[qualities-v2](../methods/qualities-v2.md)** — по умолчанию: title; доступные значения: title , count. Сортировка результатов. По названию качества либо по количеству материалов с этим качеством.
- **[translations-v2](../methods/translations-v2.md)** — по умолчанию: title; доступные значения: title , count. Сортировка результатов. По названию озвучки либо по количеству материалов с этой озвучкой.
- **[countries](../methods/countries.md)** — по умолчанию: title; доступные значения: title , count. Сортировка результатов. По названию страны либо по количеству материалов с этой страной.
- **[genres](../methods/genres.md)** — по умолчанию: title; доступные значения: title , count. Сортировка результатов. По названию жанра либо по количеству материалов с этим жанром.
- **[list](../methods/list.md)** — по умолчанию: updated_at; доступные значения: year , created_at , updated_at , kinopoisk_rating , imdb_rating , shikimori_rating. По какому полю сортировать материалы.
- ** [years](../methods/years.md)** — по умолчанию: year; доступные значения: year , count. Сортировка результатов. По году либо по количеству материалов с этим годом.

## `token`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Обязательные параметры
- **По умолчанию:** Отсутствует
- **Доступные значения:** не указаны
- **Описание:** API-токен. Необходимо указать для успешного выполнения запроса.

## `translation_id`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Необязательные параметры
- **По умолчанию:** Отсутствует
- **Доступные значения:** 714 , 714,720
- **Описание:** Фильтрация материалов по ID озвучки. ID всех озвучек можно получить через API ресурс /translations либо на странице списка озвучек .

## `translation_type`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

Описание параметра различается между методами:

- **[qualities-v2](../methods/qualities-v2.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Голосовой/Субтитры.
- **[translations-v2](../methods/translations-v2.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Голосовой/Субтитры.
- **[countries](../methods/countries.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Голосовой/Субтитры.
- **[genres](../methods/genres.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Голосовой/Субтитры.
- **[list](../methods/list.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Позволяет выводить только голосовой перевод или только субтитры.
- **[search](../methods/search.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Позволяет выводить только голосовой перевод или только субтитры.
- ** [years](../methods/years.md)** — по умолчанию: Отсутствует; доступные значения: voice , subtitles. Фильтрация материалов по типу перевода. Голосовой/Субтитры.

## `types`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md),  [years](../methods/years.md)

- **Раздел в источнике:** Необязательные параметры
- **По умолчанию:** Все типы
- **Доступные значения:** Фильмы: foreign-movie , soviet-cartoon , foreign-cartoon , russian-cartoon , anime , russian-movie Сериалы: cartoon-serial , documentary-serial , russian-serial , foreign-serial , anime-serial , multi-part-film
- **Описание:** Фильтрация материалов по их типу. Для удобства доступно большое количество типов фильмов и сериалов. Необходимые типы указываются через запятую

## `year`

**Встречается в:** [qualities-v2](../methods/qualities-v2.md), [translations-v2](../methods/translations-v2.md), [countries](../methods/countries.md), [genres](../methods/genres.md), [list](../methods/list.md), [search](../methods/search.md)

Описание параметра различается между методами:

- **[qualities-v2](../methods/qualities-v2.md)** — по умолчанию: Отсутствует; доступные значения: 0000 - 9999. Фильтрация материалов по году
- **[translations-v2](../methods/translations-v2.md)** — по умолчанию: Отсутствует; доступные значения: 0000 - 9999. Фильтрация материалов по году
- **[countries](../methods/countries.md)** — по умолчанию: Отсутствует; доступные значения: 0000 - 9999. Фильтрация материалов по году
- **[genres](../methods/genres.md)** — по умолчанию: Отсутствует; доступные значения: 0000 - 9999. Фильтрация материалов по году
- **[list](../methods/list.md)** — по умолчанию: Отсутствует; доступные значения: 1982 , 1982,2020. Фильтрация материалов по году. Если указать этот параметр, то будут выведены только материалы соответствующего года
- **[search](../methods/search.md)** — по умолчанию: Отсутствует; доступные значения: 1982 , 1982,2020. Фильтрация материалов по году. Если указать этот параметр, то будут выведены только материалы соответствующего года
