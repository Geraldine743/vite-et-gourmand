<?php

namespace Database\Factories;

use App\Models\Theme;
use App\Models\Regime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'nb_personne' => $this->faker->numberBetween(1, 10),
            'prix_par_personne' => $this->faker->randomFloat(2, 5, 100),
            'condition' => $this->faker->sentence(),
            'stock' => $this->faker->numberBetween(0, 50),
            'regime_id' => Regime::inRandomOrder()->first()->id,
            'theme_id' => Theme::inRandomOrder()->first()->id,
        ];
    }
}
