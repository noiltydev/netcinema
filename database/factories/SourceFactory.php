<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SourceProviderName;
use App\Models\Funteam;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Source>
 */
class SourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sourceable_type' => (new Funteam)->getMorphClass(),
            'sourceable_id' => Funteam::factory(),
            'provider_name' => fake()->randomElement(SourceProviderName::cases()),
            'external_id' => fake()->unique()->uuid(),
        ];
    }
}
