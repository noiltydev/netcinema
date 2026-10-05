<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\KodikTranslationType;
use PHPUnit\Framework\TestCase;

class KodikTranslationTypeTest extends TestCase
{
    public function test_values_match_kodik_filter_vocabulary(): void
    {
        $this->assertSame('voice', KodikTranslationType::VOICE->value);
        $this->assertSame('subtitles', KodikTranslationType::SUBTITLES->value);
    }

    public function test_type_can_be_resolved_from_its_value(): void
    {
        $this->assertSame(KodikTranslationType::VOICE, KodikTranslationType::from('voice'));
        $this->assertSame(KodikTranslationType::SUBTITLES, KodikTranslationType::from('subtitles'));
    }
}
