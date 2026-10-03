<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TranslationBalancer;
use App\Enums\TranslationKind;
use App\Models\Funteam;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'funteam_id' => Funteam::factory(),
            'balancer' => fake()->randomElement(TranslationBalancer::cases()),
            'external_id' => fake()->unique()->uuid(),
            'kind' => fake()->randomElement(TranslationKind::cases()),
            'locale' => 'ru',
        ];
    }
}
