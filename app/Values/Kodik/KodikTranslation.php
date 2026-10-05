<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikTranslationType;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

final readonly class KodikTranslation implements Arrayable
{
    private const string SUBTITLES_SUFFIX = '.Subtitles';

    private function __construct(
        public int $id,
        public string $title,
        public int $count,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            title: (string)$data['title'],
            count: (int)$data['count'],
        );
    }

    public function name(): string
    {
        return Str::before($this->title, self::SUBTITLES_SUFFIX);
    }

    public function slug(): string
    {
        return slugify($this->name());
    }

    public function type(): KodikTranslationType
    {
        return Str::endsWith($this->title, self::SUBTITLES_SUFFIX)
            ? KodikTranslationType::SUBTITLES
            : KodikTranslationType::VOICE;
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'count' => $this->count,
        ];
    }
}
