<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Kodik\KodikTranslationType;
use App\Values\Concerns\ReadsRequiredFields;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikTranslation implements Arrayable
{
    use ReadsRequiredFields;

    /**
     * The endpoint documents id, title and count only, so the translation kind has to be read off
     * the title: the API marks a subtitle track by suffixing its name.
     */
    private const string SUBTITLES_SUFFIX = '.Subtitles';

    private function __construct(
        public int $id,
        public string $title,
        public int $count,
    ) {}

    public static function make(int $id, string $title, int $count): self
    {
        return new self(id: $id, title: $title, count: $count);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        return self::make(
            id: (int) self::required($data, 'id'),
            title: (string) self::required($data, 'title'),
            count: (int) self::required($data, 'count'),
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
