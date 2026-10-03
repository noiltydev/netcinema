<?php

declare(strict_types=1);

namespace App\Enums;

enum TranslationBalancer: string
{
    case Alloha = 'alloha';
    case Rewall = 'rewall';
    case Lumex = 'lumex';
    case Kodik = 'kodik';
    case HDVB = 'HDVB';
}
