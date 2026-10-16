<?php

declare(strict_types=1);

namespace App\Values\Kodik;

use App\Enums\Kodik\KodikMaterialType;
use App\Values\Concerns\ReadsRequiredFields;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

/**
 * @implements Arrayable<string, mixed>
 *
 * @phpstan-consistent-constructor
 */
final readonly class KodikMaterial implements Arrayable
{
    use ReadsRequiredFields;

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
        public ?int $lastSeason,
        public ?int $lastEpisode,
        public ?int $episodesCount,
        public ?KodikBlockedSeasons $blockedSeasons,
        public ?KodikMaterialSeasons $seasons,
    ) {}

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
        ?int $lastSeason = null,
        ?int $lastEpisode = null,
        ?int $episodesCount = null,
        ?KodikBlockedSeasons $blockedSeasons = null,
        ?KodikMaterialSeasons $seasons = null,
    ): self {
        $rawTypeValue = $typeValue ?? $type->value ?? '';

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
            lastSeason: $lastSeason,
            lastEpisode: $lastEpisode,
            episodesCount: $episodesCount,
            blockedSeasons: $blockedSeasons,
            seasons: $seasons,
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $data): self
    {
        return self::make(
            id: (string) self::required($data, 'id'),
            title: (string) self::required($data, 'title'),
            typeValue: (string) self::required($data, 'type'),
            link: self::nullableString($data['link'] ?? null),
            titleOrig: self::nullableString($data['title_orig'] ?? null),
            otherTitle: self::nullableString($data['other_title'] ?? null),
            translation: isset($data['translation']) && is_array($data['translation'])
                ? KodikMaterialTranslation::fromArray($data['translation'])
                : null,
            year: self::nullableInt($data['year'] ?? null),
            kinopoiskId: self::nullableString($data['kinopoisk_id'] ?? null),
            imdbId: self::nullableString($data['imdb_id'] ?? null),
            mdlId: self::nullableString($data['mdl_id'] ?? null),
            worldartLink: self::nullableString($data['worldart_link'] ?? null),
            shikimoriId: self::nullableString($data['shikimori_id'] ?? null),
            quality: self::nullableString($data['quality'] ?? null),
            camrip: self::nullableBool($data['camrip'] ?? null),
            lgbt: self::nullableBool($data['lgbt'] ?? null),
            createdAt: self::parseDate($data['created_at'] ?? null),
            updatedAt: self::parseDate($data['updated_at'] ?? null),
            blockedCountries: self::stringList($data['blocked_countries'] ?? []),
            screenshots: self::stringList($data['screenshots'] ?? []),
            lastSeason: self::nullableInt($data['last_season'] ?? null),
            lastEpisode: self::nullableInt($data['last_episode'] ?? null),
            episodesCount: self::nullableInt($data['episodes_count'] ?? null),
            blockedSeasons: isset($data['blocked_seasons'])
                ? KodikBlockedSeasons::fromArray($data['blocked_seasons'])
                : null,
            seasons: isset($data['seasons']) && is_array($data['seasons'])
                ? KodikMaterialSeasons::fromArray($data['seasons'])
                : null,
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
            'last_season' => $this->lastSeason,
            'last_episode' => $this->lastEpisode,
            'episodes_count' => $this->episodesCount,
            'blocked_seasons' => $this->blockedSeasons?->toArray(),
            'seasons' => $this->seasons?->toArray(),
        ];
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null ? null : (string) $value;
    }

    private static function nullableInt(mixed $value): ?int
    {
        return $value === null ? null : (int) $value;
    }

    private static function nullableBool(mixed $value): ?bool
    {
        return $value === null ? null : (bool) $value;
    }

    /**
     * @return array<int, string>
     */
    private static function stringList(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_map(static fn (mixed $item): string => (string) $item, $value));
    }

    private static function parseDate(mixed $value): ?CarbonImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        return CarbonImmutable::parse((string) $value);
    }
}
