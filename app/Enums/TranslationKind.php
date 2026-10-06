<?php

declare(strict_types=1);

namespace App\Enums;

enum TranslationKind: string
{
    case DUB = 'dub';
    case SUB = 'sub';
}
