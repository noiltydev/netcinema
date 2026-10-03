<?php

declare(strict_types=1);

namespace App\Enums;

enum TranslationKind: string
{
    case Dub = 'dub';
    case Sub = 'sub';
}
