<?php

namespace Database\Factories;

use App\Models\Set;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "api_id" => uniqid(),
            "set_id" => Set::factory(),
            "name" => fake()->word(),
            "number" => fake()->numberBetween(1),
            "rarity" => fake()->randomElement([
                "Uncommon",
                "Rare",
                "Super Rare"
            ]),
            "image" => fake()->imageUrl(),
        ];
    }
}
