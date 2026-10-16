<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anime>
 */
class AnimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kind = fake()->randomElement(AnimeKind::cases());
        $year = fake()->numberBetween(1990, 2026);
        /** @var EntrySeason $season */
        $season = fake()->randomElement(EntrySeason::cases());

        return [
            'kind' => $kind,
            'rating' => fake()->randomElement(EntryRating::cases()),
            'status' => fake()->randomElement(EntryStatus::cases()),
            'title' => fake()->unique()->sentence(3),
            'slug' => fake()->unique()->slug(5),
            'aired_at' => fake()->optional(0.7)->dateTimeBetween("-{$year}-01-01", 'now'),
            'aired_year' => $year,
            'aired_season' => $season->value,
            'released_at' => fake()->optional(0.5)->dateTimeBetween("-{$year}-01-01", 'now'),
            'episodes_count' => $kind === AnimeKind::MOVIE ? 1 : fake()->numberBetween(1, 50),
            'episodes_aired' => fake()->numberBetween(0, 50),
            'duration' => fake()->numberBetween(1, 120),
            'next_episode_at' => fake()->optional(0.3)->dateTimeBetween('now', '+3 months'),
        ];
    }

    public function tv(): static
    {
        return $this->state(static fn (array $attributes) => [
            'kind' => AnimeKind::TV,
            'episodes_count' => fake()->numberBetween(12, 26),
        ]);
    }

    public function movie(): static
    {
        return $this->state(static fn (array $attributes) => [
            'kind' => AnimeKind::MOVIE,
            'episodes_count' => 1,
        ]);
    }

    public function ova(): static
    {
        return $this->state(static fn (array $attributes) => [
            'kind' => AnimeKind::OVA,
            'episodes_count' => fake()->numberBetween(1, 6),
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(static fn (array $attributes) => [
            'status' => EntryStatus::ONGOING,
            'episodes_aired' => fake()->numberBetween(1, 12),
        ]);
    }

    public function released(): static
    {
        return $this->state(static fn (array $attributes) => [
            'status' => EntryStatus::RELEASED,
            'episodes_aired' => $attributes['episodes_count'] ?? fake()->numberBetween(12, 26),
        ]);
    }

    public function anons(): static
    {
        return $this->state(static fn (array $attributes) => [
            'status' => EntryStatus::ANONS,
            'episodes_aired' => 0,
            'released_at' => null,
        ]);
    }

    public function season(int $year, EntrySeason $season): static
    {
        return $this->state(static fn (array $attributes) => [
            'aired_year' => $year,
            'aired_season' => $season->value,
        ]);
    }
}
