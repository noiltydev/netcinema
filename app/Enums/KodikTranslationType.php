<?php

declare(strict_types=1);

namespace App\Enums;

enum KodikTranslationType: string
{
    case VOICE = 'voice';
    case SUBTITLES = 'subtitles';
}
