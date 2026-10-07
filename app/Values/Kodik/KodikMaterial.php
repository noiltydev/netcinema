<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\KodikMaterialType;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

final readonly class KodikMaterial implements Arrayable
{
    /**
     * @param array<int, string> $blockedCountries
     * @param array<int, string> $screenshots
     */
    private function __construct(
        public string $id,
        public string $title,
        public string $typeValue,
        public ?KodikMaterialType $type,
        public ?string $link,
        public ?string $titleOrig,
        public ?string $otherTitle,
        public ?KodikMaterialTranslation $translation,
        public ?int $year,
        public ?string $kinopoiskId,
        public ?string $imdbId,
        public ?string $mdlId,
        public ?string $worldartLink,
        public ?string $shikimoriId,
        public ?string $quality,
        public ?bool $camrip,
        public ?bool $lgbt,
        public ?CarbonImmutable $createdAt,
        public ?CarbonImmutable $updatedAt,
        public array $blockedCountries,
        public array $screenshots,
    )
    {
    }

    /**
     * @param array<int, string> $blockedCountries
     * @param array<int, string> $screenshots
     */
    public static function make(
        string $id,
        string $title,
        ?KodikMaterialType $type = null,
        ?string $typeValue = null,
        ?string $link = null,
        ?string $titleOrig = null,
        ?string $otherTitle = null,
        ?KodikMaterialTranslation $translation = null,
        ?int $year = null,
        ?string $kinopoiskId = null,
        ?string $imdbId = null,
        ?string $mdlId = null,
        ?string $worldartLink = null,
        ?string $shikimoriId = null,
        ?string $quality = null,
        ?bool $camrip = null,
        ?bool $lgbt = null,
        ?CarbonImmutable $createdAt = null,
        ?CarbonImmutable $updatedAt = null,
        array $blockedCountries = [],
        array $screenshots = [],
    ) : self
    {
        $rawTypeValue = $typeValue ?? $type?->value ?? '';

        return new self(
            id: $id,
            title: $title,
            typeValue: $rawTypeValue,
            type: KodikMaterialType::tryFrom($rawTypeValue),
            link: $link,
            titleOrig: $titleOrig,
            otherTitle: $otherTitle,
            translation: $translation,
            year: $year,
            kinopoiskId: $kinopoiskId,
            imdbId: $imdbId,
            mdlId: $mdlId,
            worldartLink: $worldartLink,
            shikimoriId: $shikimoriId,
            quality: $quality,
            camrip: $camrip,
            lgbt: $lgbt,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            blockedCountries: $blockedCountries,
            screenshots: $screenshots,
        );
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            id: (string)($data['id'] ?? ''),
            title: (string)($data['title'] ?? ''),
            typeValue: (string)($data['type'] ?? ''),
            link: self::nullableString($data['link'] ?? null),
            titleOrig: self::nullableString($data['title_orig'] ?? null),
            otherTitle: self::nullableString($data['other_title'] ?? null),
            translation: isset($data['translation']) && is_array($data['translation'])
                ? KodikMaterialTranslation::fromArray($data['translation'])
                : null,
            year: isset($data['year']) ? (int)$data['year'] : null,
            kinopoiskId: self::nullableString($data['kinopoisk_id'] ?? null),
            imdbId: self::nullableString($data['imdb_id'] ?? null),
            mdlId: self::nullableString($data['mdl_id'] ?? null),
            worldartLink: self::nullableString($data['worldart_link'] ?? null),
            shikimoriId: self::nullableString($data['shikimori_id'] ?? null),
            quality: self::nullableString($data['quality'] ?? null),
            camrip: isset($data['camrip']) ? (bool)$data['camrip'] : null,
            lgbt: isset($data['lgbt']) ? (bool)$data['lgbt'] : null,
            createdAt: self::parseDate($data['created_at'] ?? null),
            updatedAt: self::parseDate($data['updated_at'] ?? null),
            blockedCountries: self::stringList($data['blocked_countries'] ?? []),
            screenshots: self::stringList($data['screenshots'] ?? []),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->typeValue,
            'link' => $this->link,
            'title' => $this->title,
            'title_orig' => $this->titleOrig,
            'other_title' => $this->otherTitle,
            'translation' => $this->translation?->toArray(),
            'year' => $this->year,
            'kinopoisk_id' => $this->kinopoiskId,
            'imdb_id' => $this->imdbId,
            'mdl_id' => $this->mdlId,
            'worldart_link' => $this->worldartLink,
            'shikimori_id' => $this->shikimoriId,
            'quality' => $this->quality,
            'camrip' => $this->camrip,
            'lgbt' => $this->lgbt,
            'created_at' => $this->createdAt?->toIso8601String(),
            'updated_at' => $this->updatedAt?->toIso8601String(),
            'blocked_countries' => $this->blockedCountries,
            'screenshots' => $this->screenshots,
        ];
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null ? null : (string)$value;
    }

    /**
     * @return array<int, string>
     */
    private static function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_map(
            static fn(mixed $item): string => (string)$item,
            $value,
        ));
    }

    private static function parseDate(mixed $value): ?CarbonImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        return CarbonImmutable::parse((string)$value);
    }
}
