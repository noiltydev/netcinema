<?php

declare(strict_types=1);

namespace App\Enums\Kodik;

enum KodikTranslationType: string
{
    case VOICE = 'voice';
    case SUBTITLES = 'subtitles';
}
