<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

class Kodik
{
    public static function prepareTranslationTitle(string $value): string
    {
        return Str::before($value, '.Subtitles');
    }

    public static function prepareTranslationType(string $value): string
    {
        return Str::endsWith($value, '.Subtitles') ? 'subtitles' : 'voice';
    }
}
