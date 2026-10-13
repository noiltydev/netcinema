<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntryStatus;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anime>
 */
class AnimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Anime>
     */
    protected $model = Anime::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kind = $this->faker->randomElement(AnimeKind::cases());
        $year = $this->faker->numberBetween(1990, 2026);
        $season = $this->faker->randomElement(['winter', 'spring', 'summer', 'fall']);

        return [
            'kind' => $kind,
            'rating' => $this->faker->randomElement(EntryRating::cases()),
            'status' => $this->faker->randomElement(EntryStatus::cases()),
            'title' => $this->faker->unique()->sentence(3),
            'slug' => $this->faker->unique()->slug(5),
            'aired_at' => $this->faker->optional(0.7)->dateTimeBetween("-{$year}-01-01", 'now'),
            'aired_year' => $year,
            'aired_season' => $season,
            'released_at' => $this->faker->optional(0.5)->dateTimeBetween("-{$year}-01-01", 'now'),
            'episodes_count' => $kind === AnimeKind::MOVIE ? 1 : $this->faker->numberBetween(1, 50),
            'episodes_aired' => $this->faker->numberBetween(0, 50),
            'duration' => $this->faker->numberBetween(1, 120),
            'next_episode_at' => $this->faker->optional(0.3)->dateTimeBetween('now', '+3 months'),
        ];
    }

    public function tv(): static
    {
        return $this->state(fn (array $attributes) => [
            'kind' => AnimeKind::TV,
            'episodes_count' => $this->faker->numberBetween(12, 26),
        ]);
    }

    public function movie(): static
    {
        return $this->state(fn (array $attributes) => [
            'kind' => AnimeKind::MOVIE,
            'episodes_count' => 1,
        ]);
    }

    public function ova(): static
    {
        return $this->state(fn (array $attributes) => [
            'kind' => AnimeKind::OVA,
            'episodes_count' => $this->faker->numberBetween(1, 6),
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EntryStatus::ONGOING,
            'episodes_aired' => $this->faker->numberBetween(1, 12),
        ]);
    }

    public function released(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EntryStatus::RELEASED,
            'episodes_aired' => $attributes['episodes_count'] ?? $this->faker->numberBetween(12, 26),
        ]);
    }

    public function anons(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EntryStatus::ANONS,
            'episodes_aired' => 0,
            'released_at' => null,
        ]);
    }

    public function season(int $year, string $season): static
    {
        return $this->state(fn (array $attributes) => [
            'aired_year' => $year,
            'aired_season' => $season,
        ]);
    }
}
