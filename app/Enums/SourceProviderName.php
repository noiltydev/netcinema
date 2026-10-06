<?php

declare(strict_types=1);

namespace App\Enums;

enum SourceProviderName: string
{
    // wiki
    case MYANIMELIST = 'myanimelist';
    case SHIKIMORI = 'shikimori';
    case KINOPOISK = 'kinopoisk';
    case IMDB = 'imdb';

    // balancer
    case KODIK = 'kodik';
}
