<?php

declare(strict_types=1);

namespace App\Enums;

enum TranslationBalancer: string
{
    case ALLOHA = 'alloha';
    case REWALL = 'rewall';
    case LUMEX = 'lumex';
    case KODIK = 'kodik';
    case HDVB = 'HDVB';
}
