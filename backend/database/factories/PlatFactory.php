<?php

namespace Database\Factories;

use App\Models\TypePlat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Type;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plat>
 */
class PlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre_plat' => fake()->words(3, true),
            'type_plat_id' => TypePlat::inRandomOrder()->first()->id,
            'image' => 'https://picsum.photos/200'
        ];
    }
}
